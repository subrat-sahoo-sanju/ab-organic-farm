<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ImageUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ProductImageController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'images.*' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $this->saveUploads($request, $product);

        return back()->with('success', 'Images uploaded.');
    }

    public function reorder(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate(['order' => ['required', 'array']])['order'];
        foreach ($data as $position => $imageId) {
            ProductImage::where('product_id', $product->id)->whereKey($imageId)->update(['sort_order' => $position]);
        }

        return back()->with('success', 'Image order saved.');
    }

    /** Mark an image as the primary (main) image for the product. */
    public function setPrimary(Request $request, Product $product, ProductImage $image): RedirectResponse
    {
        abort_unless($image->product_id === $product->id, 404);

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }

    public function destroy(ProductImage $image): RedirectResponse
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete(
            str_replace('storage/', '', $image->path)
        );

        $productId = $image->product_id;
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            ProductImage::where('product_id', $productId)->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image removed.');
    }

    /**
     * Persist uploaded files + assign primary if none exists yet.
     *
     * Paths are stored WITHOUT a "storage/" prefix so they resolve correctly
     * via `asset('storage/'.$image->path)` everywhere.
     */
    public function saveUploads(Request $request, Product $product): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextSort = (int) $product->images()->max('sort_order') + 1;

        foreach ((array) $request->file('images') as $file) {
            if (! $file) {
                continue;
            }
            $path = app(ImageUtility::class)->processUpload($file, 1000, 1000, "products/{$product->id}");
            if (! $path) {
                continue;
            }

            $image = $product->images()->create([
                'path' => $path,
                'thumb_path' => $path,
                'alt_text' => $product->name,
                'sort_order' => $nextSort++,
                'is_primary' => ! $hasPrimary,
            ]);
            $hasPrimary = $hasPrimary || $image->is_primary;
        }

        // Guarantee at least one primary
        if (! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }
    }
}
