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
        foreach ($request->validate(['order' => ['required', 'array']])['order'] as $position => $imageId) {
            ProductImage::where('product_id', $product->id)->whereKey($imageId)->update(['sort_order' => $position]);
        }

        return back()->with('success', 'Image order saved.');
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

    /** Persist uploaded files + assign primary if none exists yet. */
    public function saveUploads(Request $request, Product $product): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $nextSort = (int) $product->images()->max('sort_order') + 1;

        foreach ((array) $request->file('images') as $file) {
            if (! $file) {
                continue;
            }
            $path = app(ImageUtility::class)->processUpload($file, 1000, 1000, "products/{$product->id}");

            $product->images()->create([
                'path' => 'storage/'.$path,
                'thumb_path' => 'storage/'.$path,
                'alt_text' => $product->name,
                'sort_order' => $nextSort++,
                'is_primary' => ! $hasPrimary && $nextSort === 1,
            ]);
            $hasPrimary = $hasPrimary || ($nextSort === 2);
        }

        // Guarantee at least one primary
        if (! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }
    }
}
