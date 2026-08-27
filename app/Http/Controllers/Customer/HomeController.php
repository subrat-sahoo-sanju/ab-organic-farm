<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $brands = Brand::where('is_active', true)
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $brandProducts = [];
        $brandTotalCounts = [];
        foreach ($brands as $brand) {
            $brandProducts[$brand->id] = Product::query()
                ->published()
                ->where('brand_id', $brand->id)
                ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
                ->take(6)
                ->get();
            $brandTotalCounts[$brand->id] = $brand->products_count;
        }

        $data = [
            'categories' => Category::whereNull('parent_id')
                ->withCount('products')->orderBy('sort_order')->take(8)->get(),
            'featuredProducts' => $this->productRail(fn ($q) => $q->where('is_featured', true), 12),
            'bestSellers' => $this->productRail(fn ($q) => $q->where('is_best_seller', true), 12),
            'newArrivals' => $this->productRail(fn ($q) => $q->where('is_new_arrival', true), 12),
            'testimonials' => \App\Models\Review::where('status', 'approved')
                ->with('user:id,name')->orderByDesc('rating')->take(3)->get(),
            'brands' => $brands,
            'brandProducts' => $brandProducts,
            'brandTotalCounts' => $brandTotalCounts,
            'heroBanners' => Banner::running()->where('placement', 'hero')->take(2)->get(),
            'promotionalBanners' => Banner::running()->where('placement', 'promotional')->take(2)->get(),
        ];

        return view('customer.home', $data);
    }

    public function brandProductsApi(Brand $brand): JsonResponse
    {
        $offset = (int) request('offset', 0);
        $limit = (int) request('limit', 12);
        $limit = min($limit, 100);

        $products = Product::query()
            ->published()
            ->where('brand_id', $brand->id)
            ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
            ->skip($offset)
            ->take($limit)
            ->get();

        $html = view('components.product-card-grid', ['products' => $products])->render();

        return response()->json([
            'html' => $html,
            'count' => $products->count(),
            'hasMore' => $products->count() === $limit,
        ]);
    }

    protected function productRail(\Closure $scope, int $limit)
    {
        $query = Product::query()->published();
        $scope($query);

        return $query->with(['primaryImage', 'defaultVariant.inventory', 'category'])
            ->take($limit)
            ->get();
    }
}
