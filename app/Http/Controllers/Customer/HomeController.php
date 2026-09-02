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
                [$welcomeTabs, $welcomeProducts] = $this->welcomeSection($config, $count);
                $data[$key] = $this->attachSoldCount($welcomeProducts);
                $tabs[$key] = $welcomeTabs;
            }

            if ($key === 'promotional_banners') {
                $data[$key] = Banner::running()->where('placement', 'promotional')->orderBy('sort_order')->get();
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

    /** Lazy-loaded product grid for a welcome menu tab (reference 'Welcome To' menu). */
    public function welcomeTabApi(): JsonResponse
    {
        $fallback = null;
        $rawFallback = (string) request('fallback', '');
        if ($rawFallback !== '') {
            $decoded = json_decode($rawFallback, true);
            if (is_array($decoded)) {
                $fallback = $decoded;
            }
        }

        $tab = [
            'type' => (string) request('type', 'all'),
            'value' => (string) request('value', ''),
            'values' => array_values(array_filter(array_map('trim', explode(',', (string) request('values', ''))))),
            'fallback' => $fallback,
        ];
        $limit = (int) request('limit', 12);

        $products = $this->attachSoldCount($this->resolveTabProducts($tab, $limit));

        return response()->json([
            'html' => view('components.product-card-grid', ['products' => $products, 'itemClass' => 'menu-grid-item'])->render(),
            'count' => $products->count(),
        ]);
    }

    /** Build the reference-style welcome tabs (All / Ghee / Oils / Atta / Combos / Deal / Superfoods) + first-tab products. */
    protected function welcomeSection(array $config, int $count): array
    {
        $tabs = [];
        $raw = $config['tabs'] ?? null;

        if (is_array($raw) && count($raw)) {
            foreach (array_values($raw) as $item) {
                if (is_string($item)) { // legacy: plain category slug
                    $slug = trim($item);
                    $tabs[] = $this->welcomeTab([
                        'title' => ucwords(str_replace('-', ' ', $slug)),
                        'key' => $slug,
                        'type' => 'category',
                        'value' => $slug,
                    ], $count);
                    continue;
                }
                $tabs[] = $this->welcomeTab($item ?? [], $count);
            }
        }

        if (! count($tabs)) {
            foreach ($this->rootCategories(7) as $cat) {
                $tabs[] = $this->welcomeTab([
                    'title' => $cat->name,
                    'key' => 'tab-'.$cat->id,
                    'type' => 'category',
                    'value' => $cat->slug,
                ], $count);
            }
        }

        $first = $tabs[0] ?? null;

        return [$tabs, $first ? $this->resolveTabProducts($first, $count) : collect()];
    }

    protected function welcomeTab(array $t, int $count): array
    {
        $title = $t['title'] ?? 'Tab';
        $type = $t['type'] ?? 'all';
        $key = \Illuminate\Support\Str::slug($t['key'] ?? $title);

        return [
            'key' => $key ?: 'tab-'.random_int(100, 999),
            'title' => $title,
            'type' => $type,
            'url' => route('api.welcome-tab', array_filter([
                'type' => $type,
                'value' => $t['value'] ?? null,
                'values' => ! empty($t['values']) ? implode(',', $t['values']) : null,
                'fallback' => ! empty($t['fallback']) ? json_encode($t['fallback']) : null,
                'limit' => $count,
            ])),
            'active_icon' => ! empty($t['active_icon']) ? asset($t['active_icon']) : null,
            'inactive_icon' => ! empty($t['inactive_icon']) ? asset($t['inactive_icon']) : null,
            'fallback' => $t['fallback'] ?? null,
        ];
    }

    /** Products for one welcome tab type: all | deal | category | categories | keyword (+ optional fallback). */
    protected function resolveTabProducts(array $tab, int $count)
    {
        $type = $tab['type'] ?? 'all';
        $limit = max(1, min((int) $count, 100));
        $query = $this->rail();

        switch ($type) {
            case 'all':
                break;
            case 'deal':
                $query->whereHas('defaultVariant', fn ($v) => $v->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'price'));
                break;
            case 'category':
                $cat = ! empty($tab['value'])
                    ? Category::where('slug', $tab['value'])->where('is_active', true)->first()
                    : null;
                if (! $cat) {
                    return collect();
                }
                $query->whereIn('category_id', $cat->descendantIds());
                break;
            case 'categories':
                $cats = Category::whereIn('slug', array_values(array_filter((array) ($tab['values'] ?? []))))
                    ->where('is_active', true)->get();
                if (! $cats->count()) {
                    return collect();
                }
                $ids = collect($cats)->flatMap(fn ($c) => $c->descendantIds())->unique()->values();
                $query->whereIn('category_id', $ids);
                break;
            case 'keyword':
                $term = $tab['value'] ?? '';
                if ($term === '') {
                    return collect();
                }
                $query->where('name', 'like', '%'.$term.'%');
                break;
            default:
                return collect();
        }

        $products = $query->take($limit)->get();

        if ($products->isEmpty() && ! empty($tab['fallback']) && is_array($tab['fallback'])) {
            $products = $this->resolveTabProducts($tab['fallback'], $count);
        }

        return $products;
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