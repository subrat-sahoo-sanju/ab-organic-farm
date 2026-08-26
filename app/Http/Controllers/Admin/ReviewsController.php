<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewsController extends Controller
{
    public function index()
    {
        return view('admin.reviews', [
            'reviews' => Review::with(['user:id,name', 'product:id,name', 'order:id,order_number'])
                ->latest('created_at')
                ->when(request('status'), fn ($q, $s) => $q->where('status', $s))
                ->paginate(20)
                ->withQueryString(),
        ]);
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);
        $this->refreshProductRating($review->product_id);

        return back()->with('success', 'Review approved.');
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update(['status' => 'rejected']);
        $this->refreshProductRating($review->product_id);

        return back()->with('success', 'Review rejected.');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $productId = $review->product_id;
        $review->delete();
        $this->refreshProductRating($productId);

        return back()->with('success', 'Review deleted.');
    }

    protected function refreshProductRating(int $productId): void
    {
        $product = \App\Models\Product::find($productId);
        if (! $product) return;

        $approved = $product->reviews()->where('status', 'approved');

        $product->forceFill([
            'review_count' => $approved->count(),
            'rating_avg' => round((float) $approved->avg('rating'), 2),
        ])->saveQuietly();
    }
}
