<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class HomeController extends Controller
{
    const WITH = ['primaryImage', 'images', 'defaultVariant.inventory', 'category'];

    public function index(): View
    {
        $sections = HomepageSection::where('is_visible', true)->orderBy('sort_order')->get();

        $data = [];
        $tabs = [];

        foreach ($sections as $section) {
            $key = $section->key;
            $config = $section->config ?? [];
            $count = (int) ($config['product_count'] ?? 10);

            if (in_array($key, ['deals', 'trending', 'best_sellers', 'organic_picks', 'new_arrivals', 'recommended'])) {
                $data[$key] = match ($key) {
                    'deals' => $this->rail()->whereHas('defaultVariant', fn ($v) => $v->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price'))->take($count)->get(),
                    'trending' => $this->rail()->has('approvedReviews')->withCount('approvedReviews')->orderByDesc('approved_reviews_count')->take($count)->get(),
                    'best_sellers' => $this->rail()->where('is_best_seller', true)->take($count)->get(),
                    'organic_picks' => $this->rail()->where('is_organic', true)->take($count)->get(),
                    'new_arrivals' => $this->rail()->where('is_new_arrival', true)->take($count)->get(),
                    'recommended' => $this->rail()->where('is_featured', true)->orWhere('is_new_arrival', true)->inRandomOrder()->take($count)->get(),
                };
            }

            if ($key === 'hero') {
                $data[$key] = Banner::running()->where('placement', 'hero')->take(2)->get();
            }

            if ($key === 'promotional_banners') {
                $data[$key] = Banner::running()->where('placement', 'promotional')->take(2)->get();
            }

            if ($key === 'quality') {
                $quality = Banner::running()->where('placement', 'quality')->take(4)->get();
                if ($quality->isEmpty()) {
                    $quality = $this->rail()->whereHas('primaryImage')->take(4)->get();
                }
                $data[$key] = $quality->map(fn ($item) => (object) [
                    'title' => $item->title ?? $item->name ?? null,
                    'subtitle' => $item->subtitle ?? null,
                    'image' => $item->primaryImage ? $item->primaryImage->path : ($item->desktop_image ?? null),
                    'url' => $item->button_url ?? route('shop.product', $item->slug ?? ''),
                ]);
            }

            if ($key === 'welcome') {
                $data[$key] = $this->rail()->take($count)->get();
                $tabs[$key] = $this->rootCategories(6);
            }

            if (str_starts_with($key, 'focus_')) {
                $cats = $this->focusCategories($key, $config);
                $tabProducts = [];
                foreach ($cats as $c) {
                    $tabProducts[$c->id] = $this->inCategory($c)->take($count)->get();
                }
                $data[$key] = ['categories' => $cats, 'tabProducts' => $tabProducts, 'limit' => $count];
            }

            if ($key === 'trust_badges' && empty($config['items'])) {
                // ensure config defaults exist
            }

            if ($key === 'testimonials') {
                $data[$key] = \App\Models\Review::where('status', 'approved')
                    ->with(['user:id,name'])
                    ->orderByDesc('rating')
                    ->take($count)
                    ->get();
            }
        }

        return view('customer.home', [
            'homeSections' => $sections,
            'sectionData' => $data,
            'sectionTabs' => $tabs,
            'categories' => $this->rootCategories(12),
            'rootCategories' => $this->rootCategories(12),
        ]);
    }

    /** Lazy-loaded product grid for a category (used by welcome + focus tabs). */
    public function categoryProductsApi(Category $category): JsonResponse
    {
        $limit = min((int) request('limit', 8), 100);
        $products = $this->inCategory($category)->take($limit)->get();

        $html = view('components.product-card-grid', ['products' => $products])->render();

        return response()->json([
            'html' => $html,
            'count' => $products->count(),
            'hasMore' => false,
        ]);
    }

    public function brandProductsApi(Brand $brand): JsonResponse
    {
        $offset = (int) request('offset', 0);
        $limit = (int) request('limit', 12);
        $limit = min($limit, 100);

        $products = $this->rail()
            ->where('brand_id', $brand->id)
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

    /* ---------------- Internals ---------------- */

    protected function rail()
    {
        return Product::query()
            ->published()
            ->with(self::WITH);
    }

    protected function inCategory(Category $category)
    {
        return $this->rail()->whereIn('category_id', $category->descendantIds());
    }

    protected function rootCategories(int $limit)
    {
        return Category::roots()
            ->withCount('products')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->take($limit)
            ->get();
    }

    protected function focusCategories(string $key, array $config)
    {
        $configured = $config['tabs'] ?? null;

        if (is_array($configured) && count(array_filter($configured))) {
            $cats = Category::whereIn('slug', array_filter($configured))->where('is_active', true)->get();
            if ($cats->count()) {
                return $cats->values();
            }
        }

        $needle = str_contains($key, 'ghee') ? 'ghee' : 'oil';

        return Category::where('is_active', true)
            ->where('name', 'like', '%'.$needle.'%')
            ->orderBy('sort_order')
            ->take(6)
            ->get();
    }
}