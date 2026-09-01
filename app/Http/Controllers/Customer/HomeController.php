<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Brand;
use App\Models\Category;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class HomeController extends Controller
{
    const WITH = [
        'primaryImage',
        'images',
        'defaultVariant.inventory',
        'activeVariants.inventory',
        'category',
    ];

    public function index(): View
    {
        $sections = HomepageSection::where('is_visible', true)->orderBy('sort_order')->get();

        $data = [];
        $tabs = [];

        foreach ($sections as $section) {
            $key = $section->key;
            $config = $section->config ?? [];
            $count = (int) ($config['product_count'] ?? 10);

            if (in_array($key, ['deals', 'trending', 'best_sellers', 'organic_picks', 'new_arrivals', 'recommended', 'combos', 'superfoods'])) {
                $query = match ($key) {
                    'deals' => $this->rail()->whereHas('defaultVariant', fn ($v) => $v->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price')),
                    'trending' => $this->rail()->has('approvedReviews'),
                    'best_sellers' => $this->rail()->where('is_best_seller', true),
                    'organic_picks' => $this->rail()->where('is_organic', true),
                    'new_arrivals' => $this->rail()->where('is_new_arrival', true),
                    'recommended' => $this->rail()->where('is_featured', true),
                    'combos' => $this->rail()->whereHas('category', fn ($c) => $c->where('name', 'like', '%combo%')),
                    'superfoods' => $this->rail()->whereHas('category', fn ($c) => $c->where('name', 'like', '%superfood%')),
                };
                $products = $query->take($count)->get();
                if ($products->isEmpty() && in_array($key, ['combos', 'superfoods'])) {
                    $products = $this->rail()->inRandomOrder()->take($count)->get();
                }
                $data[$key] = $this->attachSoldCount($products);
            }

            if ($key === 'hero') {
                $data[$key] = Banner::running()->where('placement', 'hero')->take(3)->get();
            }

            if ($key === 'native_ingredients') {
                // Admin-configured carousel images → fallback to first products' photos.
                $carousel = $config['carousel'] ?? [];
                if (is_array($carousel) && count($carousel)) {
                    $data[$key] = collect($carousel)->map(fn ($c) => (object) [
                        'image' => $c['image'] ?? null,
                        'url' => $c['url'] ?? '',
                        'alt' => $c['alt'] ?? ($sec->title ?? ''),
                    ]);
                } else {
                    $data[$key] = $this->rail()->whereHas('primaryImage')->take(4)->get()->map(fn ($p) => (object) [
                        'image' => $p->primaryImage->path,
                        'url' => route('shop.product', $p),
                    ]);
                }
            }

            if ($key === 'quality') {
                // Admin-configured carousel images → fallback to seeded quality banners.
                $carousel = $config['carousel'] ?? [];
                if (is_array($carousel) && count($carousel)) {
                    $data[$key] = collect($carousel)->map(fn ($c) => (object) [
                        'image' => $c['image'] ?? null,
                        'url' => $c['url'] ?? '',
                        'alt' => $c['alt'] ?? ($sec->title ?? ''),
                    ]);
                } else {
                    $data[$key] = Banner::running()->where('placement', 'quality')
                        ->get()->map(fn ($b) => (object) ['image' => $b->desktop_image, 'url' => $b->button_url]);
                }
            }

            if ($key === 'logo_slider') {
                $images = $config['images'] ?? [];
                if (! empty($images['desktop'])) {
                    $data[$key] = collect([(object) ['image' => $images['desktop'], 'url' => '#']]);
                } elseif (! empty($images) && is_numeric(array_key_first($images))) {
                    $data[$key] = collect($images)->map(fn ($im) => (object) ['image' => $im['image'] ?? null, 'url' => $im['url'] ?? null]);
                } else {
                    $data[$key] = Brand::whereNotNull('logo_path')->take(12)->get()->map(fn ($b) => (object) ['image' => $b->logo_path, 'url' => '#']);
                }
            }

            if ($key === 'welcome') {
                $data[$key] = $this->attachSoldCount($this->rail()->take($count)->get());
                $tabs[$key] = $this->rootCategories(7);
            }

            if (str_starts_with($key, 'focus_')) {
                $cats = $this->focusCategories($key, $config);
                $tabProducts = [];
                foreach ($cats as $c) {
                    $tabProducts[$c->id] = $this->attachSoldCount($this->inCategory($c)->take($count)->get());
                }
                $data[$key] = ['categories' => $cats, 'tabProducts' => $tabProducts, 'limit' => $count];
            }

            if ($key === 'testimonials') {
                $data[$key] = Review::where('status', 'approved')
                    ->with(['user:id,name'])
                    ->orderByDesc('rating')
                    ->take($count)
                    ->get();
            }

            if ($key === 'logo_slider') {
                $images = $config['images'] ?? [];
                if (count($images)) {
                    $data[$key] = collect($images)->map(fn ($im) => (object) ['image' => $im['image'] ?? null, 'url' => $im['url'] ?? null]);
                } else {
                    $data[$key] = Brand::whereNotNull('logo_path')->take(12)->get()->map(fn ($b) => (object) ['image' => $b->logo_path, 'url' => '#']);
                }
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

    /** Lazy-loaded product grid for a category (welcome + focus tabs). */
    public function categoryProductsApi(Category $category): JsonResponse
    {
        $limit = min((int) request('limit', 8), 100);
        $products = $this->attachSoldCount($this->inCategory($category)->take($limit)->get());

        $html = view('components.product-card-grid', ['products' => $products])->render();

        return response()->json(['html' => $html, 'count' => $products->count(), 'hasMore' => false]);
    }

    public function brandProductsApi(Brand $brand): JsonResponse
    {
        $offset = (int) request('offset', 0);
        $limit = min((int) request('limit', 12), 100);

        $products = $this->attachSoldCount($this->rail()->where('brand_id', $brand->id)->skip($offset)->take($limit)->get());

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
            ->with(self::WITH)
            ->withAvg('reviews', 'rating')
            ->withCount('reviews');
    }

    protected function inCategory(Category $category)
    {
        return $this->rail()->whereIn('category_id', $category->descendantIds());
    }

    protected function rootCategories(int $limit)
    {
        return Category::roots()->where('is_active', true)->orderBy('sort_order')->take($limit)->get();
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

    /** Attach a "sold in last 7 days" total to a product collection (avoids N+1). */
    protected function attachSoldCount($products)
    {
        if (! $products->count()) {
            return $products;
        }

        $ids = ProductVariant::whereIn('product_id', $products->pluck('id'))->pluck('id');

        $sold = $ids->count()
            ? DB::table('order_items')
                ->join('orders', 'orders.id', '=', 'order_items.order_id')
                ->whereIn('order_items.product_variant_id', $ids)
                ->where('orders.created_at', '>=', now()->subDays(7))
                ->groupBy('order_items.product_variant_id')
                ->selectRaw('order_items.product_variant_id, SUM(order_items.quantity) as qty')
                ->pluck('qty', 'order_items.product_variant_id')
            : collect();

        $perProduct = [];
        foreach ($products->loadMissing('activeVariants') as $p) {
            $total = 0;
            foreach ($p->activeVariants as $v) {
                $total += (int) ($sold[$v->id] ?? 0);
            }
            $perProduct[$p->id] = $total;
        }

        $products->each(fn ($p) => $p->sold_count = $perProduct[$p->id] ?? 0);

        return $products;
    }
}