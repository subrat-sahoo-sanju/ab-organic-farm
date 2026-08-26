<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function __construct(protected InventoryService $inventory) {}

    public function store(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'order_id' => ['required', 'exists:orders,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:120'],
            'body' => ['required', 'string', 'min:10', 'max:2000'],
        ]);

        $order = Order::find($data['order_id']);

        // Policy checks: own order + delivered + contains the product + not already reviewed
        abort_unless($order && $order->user_id === auth()->id(), 403);
        abort_unless($order->status?->value === 'delivered', 403, 'You can review after delivery.');
        $purchased = $order->items()->where('product_id', $data['product_id'])->exists();
        abort_unless($purchased, 403, 'This product was not part of this order.');

        $existing = Review::where('product_id', $data['product_id'])
            ->where('user_id', auth()->id())
            ->where('order_id', $order->id)
            ->first();

        if ($existing) {
            return back()->with('error', 'You have already reviewed this product for this order.');
        }

        DB::transaction(function () use ($data, $order) {
            Review::create([
                ...$data,
                'user_id' => auth()->id(),
                'status' => 'pending',
            ]);

            $this->refreshProductRating((int) $data['product_id']);
        });

        return back()->with('success', 'Thank you! Your review is pending approval.');
    }

    protected function refreshProductRating(int $productId): void
    {
        $product = Product::find($productId);
        $approved = $product->reviews()->where('status', 'approved');

        $product->forceFill([
            'review_count' => $approved->count(),
            'rating_avg' => round((float) $approved->avg('rating'), 2),
        ])->saveQuietly();
    }
}
