<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\UserRole;
use App\Models\Address;
use App\Models\DeliveryAssignment;
use App\Models\DeliveryPerson;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use App\Models\User;
use App\Services\CodService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $cod = app(CodService::class);

        // ---- Demo customers ----
        $customers = [
            ['Ankita Mohanty', 'ankita@example.com', '9437011111'],
            ['Rahul Panda', 'rahul@example.com', '9437022222'],
            ['Sneha Rath', 'sneha@example.com', '9437033333'],
            ['Debashish Nayak', 'debashish@example.com', '9437044444'],
            ['Priyanka Sahoo', 'priyanka@example.com', '9437055555'],
        ];

        foreach ($customers as $i => [$name, $email, $phone]) {
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'uuid' => (string) Str::uuid(),
                    'name' => $name,
                    'phone' => $phone,
                    'password' => Hash::make('password'),
                    'is_active' => true,
                ]
            );
            $user->roles()->syncWithoutDetaching([
                \App\Models\Role::where('name', UserRole::Customer->value)->value('id'),
            ]);

            Address::updateOrCreate(
                ['user_id' => $user->id, 'house_no' => 'House '.($i + 11)],
                [
                    'label' => 'home',
                    'name' => $name,
                    'phone' => $phone,
                    'street' => 'Sahid Nagar Main Road',
                    'area' => 'Saheed Nagar',
                    'landmark' => 'Near Community Centre',
                    'city' => 'Bhubaneswar',
                    'state' => 'Odisha',
                    'pincode' => '751007',
                    'is_default' => true,
                ]
            );
        }

        // ---- Orders across the lifecycle ----
        $statuses = [
            OrderStatus::Delivered,
            OrderStatus::Delivered,
            OrderStatus::OutForDelivery,
            OrderStatus::Packed,
            OrderStatus::Confirmed,
            OrderStatus::Pending,
            OrderStatus::Cancelled,
        ];

        foreach (User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))->get() as $index => $user) {
            $status = $statuses[$index % count($statuses)];
            $address = $user->defaultAddress()->first() ?? $user->addresses()->first();

            if (! $address) {
                continue;
            }

            $order = Order::create([
                'order_number' => sprintf('ORD-%s-%06d', now()->format('Y'), 1000 + $index),
                'user_id' => $user->id,
                'status' => OrderStatus::Pending,
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
                'placed_at' => now()->subDays($index + 1),
            ]);

            $picked = Product::with('variants')->inRandomOrder()->take(random_int(2, 4))->get();
            $subtotal = 0;

            foreach ($picked as $product) {
                /** @var ProductVariant $variant */
                $variant = $product->variants->first();
                $qty = random_int(1, 2);
                $lineTotal = round($variant->effectivePrice() * $qty, 2);
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variant_id' => $variant->id,
                    'product_name' => $product->name,
                    'variant_name' => $variant->name,
                    'sku' => $variant->sku,
                    'image_path' => $product->images->first()?->path,
                    'quantity' => $qty,
                    'unit_price' => $variant->effectivePrice(),
                    'line_discount' => 0,
                    'line_total' => $lineTotal,
                ]);
            }

            $delivery = $subtotal >= 999 ? 0 : 49;
            $total = $subtotal + $delivery;

            $order->update([
                'subtotal' => $subtotal,
                'delivery_charge' => $delivery,
                'grand_total' => $total,
                'status' => $status,
                'confirmed_at' => in_array($status, [OrderStatus::Pending, OrderStatus::Cancelled]) ? null : now(),
                'delivered_at' => $status === OrderStatus::Delivered ? now() : null,
                'cancelled_at' => $status === OrderStatus::Cancelled ? now() : null,
                'cancellation_reason' => $status === OrderStatus::Cancelled ? 'Changed my mind — ordered by mistake.' : null,
            ]);

            Payment::create([
                'order_id' => $order->id,
                'method' => 'cod',
                'amount' => $total,
                'status' => 'pending',
            ]);

            // Status history trail
            $flow = [null, OrderStatus::Confirmed];
            if (! in_array($status, [OrderStatus::Pending, OrderStatus::Cancelled])) {
                $trail = match ($status) {
                    OrderStatus::Confirmed => [OrderStatus::Confirmed],
                    OrderStatus::Packed => [OrderStatus::Confirmed, OrderStatus::Packed],
                    OrderStatus::Assigned, OrderStatus::OutForDelivery, OrderStatus::Delivered => [OrderStatus::Confirmed, OrderStatus::Packed, OrderStatus::Assigned],
                    default => [],
                };
                foreach ($trail as $s) {
                    OrderStatusHistory::create([
                        'order_id' => $order->id,
                        'from_status' => null,
                        'to_status' => $s->value,
                        'note' => 'Seeded demo order',
                    ]);
                }
            }
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'from_status' => null,
                'to_status' => OrderStatus::Pending->value,
                'note' => 'Order placed',
            ]);
            if ($status === OrderStatus::Cancelled) {
                OrderStatusHistory::create([
                    'order_id' => $order->id,
                    'from_status' => OrderStatus::Confirmed->value,
                    'to_status' => OrderStatus::Cancelled->value,
                    'note' => $order->cancellation_reason,
                ]);
            }

            // Delivery assignment for dispatched orders
            if (in_array($status, [OrderStatus::Assigned, OrderStatus::OutForDelivery, OrderStatus::Delivered])) {
                $person = DeliveryPerson::inRandomOrder()->first();

                DeliveryAssignment::create([
                    'order_id' => $order->id,
                    'delivery_person_id' => $person->id,
                    'status' => match ($status) {
                        OrderStatus::Assigned => \App\Enums\DeliveryAssignmentStatus::Assigned,
                        OrderStatus::OutForDelivery => \App\Enums\DeliveryAssignmentStatus::OutForDelivery,
                        default => \App\Enums\DeliveryAssignmentStatus::Delivered,
                    },
                    'assigned_at' => now()->subDay(),
                    'delivered_at' => $status === OrderStatus::Delivered ? now() : null,
                ]);

                // COD collected for delivered orders
                if ($status === OrderStatus::Delivered) {
                    $cod->collect($order->payment()->first(), (float) $total, $person->id);
                }
            }

            // Reviews on delivered orders
            if ($status === OrderStatus::Delivered) {
                foreach ($order->items->take(2) as $item) {
                    Review::updateOrCreate(
                        ['product_id' => $item->product_id, 'user_id' => $user->id, 'order_id' => $order->id],
                        [
                            'rating' => random_int(4, 5),
                            'title' => 'Genuinely fresh',
                            'body' => 'Packaging was neat and the quality is clearly better than what I get locally. Will reorder.',
                            'status' => 'approved',
                        ]
                    );
                }
            }
        }

        // Refresh rating aggregates
        Product::query()->each(function ($p) {
            $approved = $p->approvedReviews();
            $p->updateQuietly([
                'review_count' => $approved->count(),
                'rating_avg' => round((float) $approved->avg('rating'), 2) ?: 0,
            ]);
        });
    }
}
