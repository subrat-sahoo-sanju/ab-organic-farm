<?php

namespace App\Http\Controllers\Customer;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(protected CartService $carts) {}

    public function index(): View
    {
        return view('customer.account.orders', [
            'orders' => auth()->user()->orders()
                ->with(['items', 'payment'])
                ->latest('placed_at')
                ->paginate(10),
        ]);
    }

    public function show(Order $order): View
    {
        $this->authorizeOrder($order);

        $order->load([
            'items.product',
            'payment.codCollection',
            'statusHistories.changedBy',
            'activeAssignment.deliveryPerson.user',
            'coupon',
        ]);

        $canReview = $order->status === OrderStatus::Delivered;

        return view('customer.account.order-show', [
            'order' => $order,
            'timeline' => $order->timeline(),
            'canReview' => $canReview,
            'reviewedProductIds' => auth()->user()->reviews()->where('order_id', $order->id)->pluck('product_id'),
        ]);
    }

    public function cancel(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        if (! in_array($order->status, OrderStatus::customerCancellable(), true)) {
            return back()->with('error', 'This order can no longer be cancelled. Please contact support.');
        }

        app(\App\Services\OrderService::class)->transition(
            $order,
            OrderStatus::Cancelled,
            request('reason') ?? 'Cancelled by customer',
            auth()->user()
        );

        return back()->with('success', 'Your order has been cancelled.');
    }

    /** One-click reorder: adds every still-purchasable item to the cart. */
    public function reorder(Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $added = 0;
        foreach ($order->items as $item) {
            $variant = \App\Models\ProductVariant::with('inventory', 'product')->find($item->product_variant_id);

            if (! $variant || ! $variant->is_active || $variant->product?->status !== 'active') {
                continue;
            }
            try {
                $this->carts->add($variant, 1);
                $added++;
            } catch (\DomainException) {
                continue;
            }
        }

        return $added > 0
            ? redirect()->route('cart.index')->with('success', "{$added} item(s) added to your basket.")
            : back()->with('error', 'None of these items are currently available.');
    }

    protected function authorizeOrder(Order $order): void
    {
        abort_unless($order->user_id === auth()->id(), 403);
    }
}
