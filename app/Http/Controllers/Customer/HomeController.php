<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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
        foreach ($brands as $brand) {
            $brandProducts[$brand->id] = Product::query()
                ->published()
                ->where('brand_id', $brand->id)
                ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
                ->take(4)
                ->get();
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
            'heroBanners' => Banner::running()->where('placement', 'hero')->take(2)->get(),
            'promotionalBanners' => Banner::running()->where('placement', 'promotional')->take(2)->get(),
        ];

        return view('customer.home', $data);
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
