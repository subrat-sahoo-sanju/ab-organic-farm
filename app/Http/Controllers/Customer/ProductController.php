<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\RecentlyViewed;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function show(Product $product): View
    {
        abort_unless($product->status === 'active' && $product->published_at, 404);

        $product->load([
            'category.parent',
            'brand',
            'images',
            'activeVariants.inventory',
            'approvedReviews' => fn ($q) => $q->with('user')->take(8),
            'boughtTogether' => fn ($q) => $q->published()->with('primaryImage', 'defaultVariant'),
            'relatedProducts' => fn ($q) => $q->published()->with('primaryImage', 'defaultVariant'),
        ]);

        $product->increment('view_count');
        $this->trackRecentView($product);

        $recentlyViewed = collect();
        if (auth()->check()) {
            $ids = RecentlyViewed::where('user_id', auth()->id())
                ->where('product_id', '!=', $product->id)
                ->latest('viewed_at')
                ->limit(6)
                ->pluck('product_id');
            $recentlyViewed = Product::published()->whereIn('id', $ids)->with('primaryImage', 'defaultVariant')->get();
        }

        return view('customer.product-show', [
            'product' => $product,
            'reviews' => $product->reviews()->where('status', 'approved')->with('user:id,name')->latest()->take(12)->get(),
            'ratingBreakdown' => $this->ratingBreakdown($product),
            'recentlyViewed' => $recentlyViewed,
            'inWishlist' => auth()->check() && auth()->user()->wishlistItems()->where('product_id', $product->id)->exists(),
        ]);
    }

    protected function trackRecentView(Product $product): void
    {
        RecentlyViewed::create([
            'user_id' => auth()->id(),
            'session_id' => auth()->check() ? null : session()->getId(),
            'product_id' => $product->id,
            'viewed_at' => now(),
        ]);
    }

    protected function ratingBreakdown(Product $product): array
    {
        $counts = $product->reviews()
            ->selectRaw('rating, count(*) as total')
            ->where('status', 'approved')
            ->groupBy('rating')
            ->pluck('total', 'rating');

        return collect(range(5, 1))->map(fn ($star) => [
            'star' => $star,
            'count' => (int) ($counts[$star] ?? 0),
        ])->all();
    }
}
