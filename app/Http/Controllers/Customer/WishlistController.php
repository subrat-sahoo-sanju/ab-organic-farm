<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $products = Product::query()
            ->published()
            ->whereHas('wishlistItems', fn ($q) => $q->where('user_id', auth()->id()))
            ->with(['primaryImage', 'defaultVariant.inventory'])
            ->get();

        return view('customer.account.wishlist', ['products' => $products]);
    }

    public function toggle(Product $product)
    {
        $user = auth()->user();
        $exists = $user->wishlistItems()->where('product_id', $product->id)->exists();

        if ($exists) {
            $user->wishlistItems()->where('product_id', $product->id)->delete();
            $message = 'Removed from wishlist.';
        } else {
            $user->wishlistItems()->create(['product_id' => $product->id]);
            $message = 'Saved to your wishlist.';
        }

        if (request()->wantsJson()) {
            return response()->json([
                'inWishlist' => ! $exists,
                'count' => $user->wishlistItems()->count(),
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }
}
