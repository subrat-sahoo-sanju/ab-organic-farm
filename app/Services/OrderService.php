<?php

namespace App\Services;

use App\Enums\InventoryTxnType;
use App\Enums\OrderStatus;
use App\Events\OrderStatusChanged;
use App\Models\Address;
use App\Models\Coupon;
use App\Models\Cart;
use App\Models\Inventory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(
        protected PricingService $pricing,
        protected InventoryService $inventory,
        protected CouponService $coupons,
    ) {}

    /**
     * Create a COD order transactionally with full server-side re-validation.
     *
     * @throws \DomainException|\Illuminate\Validation\ValidationException
     */
    public function placeFromCart(User $user, Address $address, string $idempotencyKey): Order
    {
        return DB::transaction(function () use ($user, $address, $idempotencyKey) {
            // Idempotency: same key returns the existing order (prevents double-submit)
            if ($existing = Order::where('ip_address', request()->ip())
                ->where('user_id', $user->id)
                ->where('created_at', '>=', now()->subMinutes(5))
                ->get()
                ->first(fn ($o) => hash('sha256', $o->id.$o->order_number) === $idempotencyKey)) {
                return $existing;
            }

            $cart = Cart::with(['items.variant.inventory', 'coupon'])->where('user_id', $user->id)->first();

            if (! $cart || $cart->items()->count() === 0) {
                throw new \DomainException('Your cart is empty.');
            }

            $breakdown = $this->pricing->forCart($cart);
            $unavailable = $breakdown['lines']->reject(fn ($l) => $l['in_stock']);

            if ($unavailable->isNotEmpty()) {
                throw new \DomainException(
                    'Some items are no longer available in requested quantity: '
                    .$unavailable->map(fn ($l) => $l['item']->product->name)->unique()->implode(', ')
                );
            }

            /** @var Order $order */
            $order = Order::create([
                'order_number' => $this->nextOrderNumber(),
                'user_id' => $user->id,
                'status' => OrderStatus::Pending,
                'subtotal' => $breakdown['subtotal'],
                'product_discount' => $breakdown['product_discount'],
                'coupon_discount' => $breakdown['coupon_discount'],
                'delivery_charge' => $breakdown['delivery_charge'],
                'grand_total' => $breakdown['grand_total'],
                'coupon_id' => $cart->coupon?->id,
                // address snapshot
                'ship_name' => $address->name,
                'ship_phone' => $address->phone,
                'ship_house_no' => $address->house_no,
                'ship_street' => $address->street,
                'ship_area' => $address->area,
                'ship_landmark' => $address->landmark,
                'ship_city' => $address->city,
                'ship_state' => $address->state,
                'ship_pincode' => $address->pincode,
                'address_id' => $address->id,
                'payment_method' => 'cod',
                'placed_at' => now(),
                'ip_address' => request()->ip(),
                'user_agent' => substr((string) request()->userAgent(), 0, 500),
            ]);

            foreach ($breakdown['lines'] as $line) {
                $item = $line['item'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'product_variant_id' => $item->variant->id,
                    'product_name' => $item->product->name,
                    'variant_name' => $item->variant->name,
                    'sku' => $item->variant->sku,
                    'image_path' => $item->product->images->firstWhere('is_primary')?->path
                        ?? $item->product->images->first()?->path,
                    'quantity' => $item->quantity,
                    'unit_price' => $line['unit_effective'],
                    'line_discount' => round(($line['unit_list'] - $line['unit_effective']) * $item->quantity, 2),
                    'line_total' => $line['line_total'],
                ]);

                // Reserve stock immediately; converted to sale on dispatch
                $this->inventory->adjust(
                    $item->variant->inventory ?? Inventory::firstOrCreate(['product_variant_id' => $item->variant->id]),
                    $item->quantity,
                    InventoryTxnType::Reservation,
                    'Order '.$order->order_number,
                    $order
                );
            }

            Payment::create([
                'order_id' => $order->id,
                'method' => 'cod',
                'amount' => $order->grand_total,
                'status' => 'pending',
            ]);

            if ($cart->coupon_id && $order->coupon_discount > 0) {
                $this->coupons->recordUsage($cart->coupon, $user, $order);
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => OrderStatus::Pending->value,
                'note' => 'Order placed',
                'changed_by' => null,
            ]);

            event(new \App\Events\OrderPlaced($order));

            // Clear cart
            $cart->items()->delete();
            $cart->update(['coupon_id' => null]);

            session()->put('last_order_token', hash('sha256', $order->id.$order->order_number));

            return $order;
        }, 3);
    }

    /**
     * Central status transition — enforces the state machine + side effects.
     */
    public function transition(Order $order, OrderStatus $to, ?string $note = null, ?User $actor = null): Order
    {
        $from = $order->status;

        if (! $from->canTransitionTo($to)) {
            throw new \DomainException("Cannot change status from {$from->label()} to {$to->label()}.");
        }

        DB::transaction(function () use ($order, $from, $to, $note, $actor) {
            $updates = ['status' => $to];

            match ($to) {
                OrderStatus::Confirmed => $updates['confirmed_at'] = now(),
                OrderStatus::Delivered => $updates['delivered_at'] = now(),
                OrderStatus::Cancelled => array_merge($updates, [
                    'cancelled_at' => now(),
                    'cancelled_by' => $actor?->id,
                    'cancellation_reason' => $note,
                ]),
                default => null,
            };

            $order->update($updates);

            // Stock side effects
            if ($to === OrderStatus::OutForDelivery) {
                foreach ($order->items as $item) {
                    if ($inv = $item->product_variant_id ? Inventory::where('product_variant_id', $item->product_variant_id)->first() : null) {
                        $this->inventory->commitReservation($inv, $item->quantity, $order);
                    }
                }
            }

            if ($to === OrderStatus::Cancelled || $to === OrderStatus::Returned) {
                foreach ($order->items as $item) {
                    if ($inv = $item->product_variant_id ? Inventory::where('product_variant_id', $item->product_variant_id)->first() : null) {
                        if ($this->wasDispatched($from)) {
                            $this->inventory->restock($inv, $item->quantity, ucfirst($to->value).' order '.$order->order_number, $order);
                            // decrement sold counters
                            $item->product?->decrement('sold_count');
                        } else {
                            $this->inventory->releaseReservation($inv, $item->quantity, 'Cancelled order '.$order->order_number, $order);
                        }
                    }
                }
            }

            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => $from->value,
                'to_status' => $to->value,
                'note' => $note,
                'changed_by' => $actor?->id,
            ]);
        });

        event(new OrderStatusChanged($order, $from, $to));

        return $order->refresh();
    }

    protected function wasDispatched(OrderStatus $status): bool
    {
        return in_array($status, [OrderStatus::OutForDelivery, OrderStatus::Delivered], true);
    }

    protected function nextOrderNumber(): string
    {
        $year = now()->format('Y');
        $seq = (int) Order::query()->whereYear('created_at', $year)->max('id');

        return sprintf('ORD-%s-%06d', $year, $seq + 1);
    }
}
