<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function categories(): View
    {
        return view('customer.categories-all', [
            'categories' => Category::whereNull('parent_id')
                ->withCount('products')
                ->orderBy('sort_order')
                ->get(),
        ]);
    }

    public function all(): View|\Illuminate\Http\JsonResponse
    {
        $perPage = 15;
        $catSlug = request('cat');
        $category = $catSlug ? Category::where('slug', $catSlug)->first() : null;

        $products = Product::query()
            ->published()
            ->when($category, fn (Builder $q) => $q->inCategoryTree($category))
            ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
            ->orderByDesc('sold_count')
            ->paginate($perPage)
            ->withQueryString();

        if (request()->ajax() && request('page', 1) > 1) {
            return response()->json([
                'html' => view('customer.partials.product-grid', ['products' => $products])->render(),
                'nextPageUrl' => $products->nextPageUrl(),
                'hasMorePages' => $products->hasMorePages(),
            ]);
        }

        return view('customer.all-products', [
            'products' => $products,
            'rootCategories' => Category::whereNull('parent_id')
                ->where('is_active', true)
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
            $view = request('view') === 'ajax' ? 'customer.partials.product-carousel' : 'customer.partials.product-grid';

            return response()->json([
                'html' => view($view, ['products' => $products])->render(),
                'nextPageUrl' => $products->nextPageUrl(),
                'hasMorePages' => $products->hasMorePages(),
            ]);
        }

        // Resolve section data for admin-configured sections
        $sectionProducts = $this->resolveSectionData($category);

        return view('customer.category-show', [
            'category' => $category,
            'products' => $products,
            'subcategories' => $category->children()->orderBy('sort_order')->get(),
            'rootCategories' => Category::whereNull('parent_id')
                ->where('is_active', true)
                ->withCount('products')
                ->orderBy('sort_order')
                ->get(),
            'sectionData' => $sectionProducts,
        ]);
    }

    /**
     * Resolve product data for each configured section on the category page.
     */
    protected function resolveSectionData(Category $category): array
    {
        $sections = $category->sections ?? [];
        $data = [];

        // Pre-fetch root categories for tab data
        $rootCategories = Category::whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        foreach ($sections as $index => $section) {
            $type = $section['type'] ?? '';
            $key = "section_{$index}";

            match ($type) {
                'welcome' => $data[$key] = $this->getWelcomeData($rootCategories),
                'featured_products' => $data[$key] = $this->getFeaturedProducts($category, $section),
                'cross_sell' => $data[$key] = $this->getCrossSellProducts($category, $section),
                default => null,
            };
        }

        return $data;
    }

    /**
     * Build tab data for the Welcome section — root categories as tabs
     * with top-selling products from each.
     */
    protected function getWelcomeData($rootCategories): array
    {
        $tabs = [];
        $allProducts = collect();

        foreach ($rootCategories as $cat) {
            $products = Product::published()
                ->inCategoryTree($cat)
                ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
                ->orderByDesc('sold_count')
                ->limit(12)
                ->get();

            $tabs[] = [
                'key' => $cat->slug,
                'title' => $cat->name,
                'active_icon' => $cat->image_path ? asset('storage/' . $cat->image_path) : asset('images/nav/nav-category.svg'),
                'inactive_icon' => $cat->image_path ? asset('storage/' . $cat->image_path) : asset('images/nav/nav-category.svg'),
                'see_all' => route('shop.category', $cat->slug),
            ];

            $allProducts = $allProducts->merge($products);
        }

        return [
            'tabs' => $tabs,
            'products' => $allProducts->unique('id')->take(20),
        ];
    }

    /**
     * Get featured products for a section (admin-selected or top-selling).
     */
    protected function getFeaturedProducts(Category $category, array $section): \Illuminate\Support\Collection
    {
        $productIds = $section['config']['product_ids'] ?? [];

        if (!empty($productIds)) {
            return Product::published()
                ->whereIn('id', $productIds)
                ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
                ->get();
        }

        // Fallback: top-selling products in this category tree
        return Product::published()
            ->inCategoryTree($category)
            ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
            ->orderByDesc('sold_count')
            ->limit(8)
            ->get();
    }

    /**
     * Get cross-sell products from other categories.
     */
    protected function getCrossSellProducts(Category $category, array $section): \Illuminate\Support\Collection
    {
        $productIds = $section['config']['product_ids'] ?? [];

        if (!empty($productIds)) {
            return Product::published()
                ->whereIn('id', $productIds)
                ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
                ->get();
        }

        // Fallback: popular products from OTHER categories
        return Product::published()
            ->where('category_id', '!=', $category->id)
            ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
            ->orderByDesc('sold_count')
            ->limit(8)
            ->get();
    }
}
