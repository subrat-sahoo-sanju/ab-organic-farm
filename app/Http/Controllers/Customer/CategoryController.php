<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function all(): View
    {
        return view('customer.categories-all', [
            'categories' => Category::whereNull('parent_id')
                ->withCount('products')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function show(Category $category): View|\Illuminate\Http\JsonResponse
    {
        $filters = request()->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'min' => ['nullable', 'numeric', 'min:0'],
            'max' => ['nullable', 'numeric', 'min:0'],
            'rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'in_stock' => ['nullable', 'boolean'],
            'organic' => ['nullable', 'boolean'],
            'brand' => ['nullable', 'integer'],
            'sort' => ['nullable', 'in:popular,price_asc,price_desc,newest,rating,discount'],
        ]);

        $products = Product::query()
            ->published()
            ->inCategoryTree($category)
            ->when($filters['q'] ?? null, fn (Builder $q, $v) => $q->where(fn ($w) => $w
                ->where('name', 'like', "%{$v}%")
                ->orWhere('short_description', 'like', "%{$v}%")))
            ->when(isset($filters['min']), fn (Builder $q) => $q->where('regular_price', '>=', $filters['min']))
            ->when(isset($filters['max']), fn (Builder $q) => $q->where('regular_price', '<=', $filters['max']))
            ->when($filters['rating'] ?? null, fn (Builder $q, $v) => $q->where('rating_avg', '>=', $v))
            ->when($filters['organic'] ?? null, fn (Builder $q) => $q->where('is_organic', true))
            ->when($filters['in_stock'] ?? null, fn (Builder $q) => $q->whereHas(
                'variants.inventory',
                fn (Builder $q) => $q->whereColumn('stock', '>', 'reserved')
            ))
            ->when($filters['brand'] ?? null, fn (Builder $q, $v) => $q->where('brand_id', $v))
            ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
            ->when($filters['sort'] ?? null, function (Builder $q, $sort) {
                match ($sort) {
                    'price_asc' => $q->orderByRaw('COALESCE(sale_price, regular_price) ASC'),
                    'price_desc' => $q->orderByRaw('COALESCE(sale_price, regular_price) DESC'),
                    'newest' => $q->orderByDesc('published_at'),
                    'rating' => $q->orderByDesc('rating_avg'),
                    'discount' => $q->orderByRaw('(regular_price - COALESCE(sale_price, regular_price)) / NULLIF(regular_price,0) DESC'),
                    default => $q->orderByDesc('sold_count'),
                };
            }, fn (Builder $q) => $q->orderByDesc('sold_count'))
            ->paginate(12)
            ->withQueryString();

        if (request()->ajax()) {
            return response()->json([
                'html' => view('customer.partials.product-grid', ['products' => $products])->render(),
                'nextPageUrl' => $products->nextPageUrl(),
                'hasMorePages' => $products->hasMorePages(),
            ]);
        }

        return view('customer.category-show', [
            'category' => $category,
            'products' => $products,
            'subcategories' => $category->children()->orderBy('sort_order')->get(),
            'rootCategories' => Category::whereNull('parent_id')
                ->where('is_active', true)
                ->withCount('products')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }
}
