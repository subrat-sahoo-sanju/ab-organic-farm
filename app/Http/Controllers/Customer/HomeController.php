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

    /** Default leaf mark — used whenever a tab has no icon configured or the file is missing. */
    protected const DEFAULT_ICON_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#1F5C3F" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>';

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
                $validLocal = static function ($v) {
                    if (is_string($v) && trim($v) !== '') {
                        return trim($v);
                    }
                    if (is_array($v) && isset($v['image']) && is_string($v['image']) && $v['image'] !== '') {
                        return $v['image'];
                    }
                    return null;
                };
                // 1) Admin-uploaded multi-logo strip (config.logos).
                $customLogos = collect($config['logos'] ?? [])
                    ->map(fn ($v) => $validLocal($v))
                    ->filter();
                if ($customLogos->isNotEmpty()) {
                    $data[$key] = $customLogos->map(fn ($img) => (object) ['image' => $img, 'url' => '#']);
                } else {
                    // 2) Single configured image slot.
                    $images = $config['images'] ?? [];
                    $desktop = $images['desktop'] ?? (is_array($images) ? reset($images) : null);
                    if ($img = $validLocal($desktop)) {
                        $data[$key] = collect([(object) ['image' => $img, 'url' => '#']]);
                    } else {
                        // 3) Fall back to the brands logos.
                        $data[$key] = Brand::whereNotNull('logo_path')->take(12)->get()->map(fn ($b) => (object) ['image' => $b->logo_path, 'url' => '#']);
                    }
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
                [$focusTabs, $focusProducts] = $this->focusSection($key, $config, $count);
                $data[$key] = $this->attachSoldCount($focusProducts);
                $tabs[$key] = $focusTabs;
            }

            if ($key === 'testimonials') {
                $data[$key] = Review::where('status', 'approved')
                    ->with(['user:id,name'])
                    ->orderByDesc('rating')
                    ->take($count)
                    ->get();
            }

            // logo_slider is handled above (line ~93). This duplicate block used to
            // override it with null-image entries whenever the config held empty image
            // slots, which made "Trusted by" render the empty-state instead of logos.
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
        $limit = min(max((int) request('limit', 12), 1), 100);
        $offset = max((int) request('offset', 0), 0);

        // Fetch limit+1 so we can report hasMore without an extra pass.
        $all = $this->attachSoldCount($this->resolveTabProducts($tab, $offset + $limit));
        $products = $all->slice($offset)->take($limit)->values();
        $hasMore = $all->count() > $offset + $limit;

        return response()->json([
            'html' => view('components.product-card-grid', ['products' => $products, 'itemClass' => 'menu-grid-item'])->render(),
            'count' => $products->count(),
            'hasMore' => $hasMore,
        ]);
    }

    /** Lazy-loaded recently viewed cards (reference 'You were checking these out earlier.' rail). */
    public function recentViewedProductsApi(): JsonResponse
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', explode(',', (string) request('ids', ''))))));
        $ids = array_slice($ids, 0, 12);

        if (! count($ids)) {
            return response()->json(['html' => '', 'count' => 0]);
        }

        $byId = $this->rail()->whereIn('id', $ids)->get()->keyBy('id');
        $products = collect($ids)->map(fn ($id) => $byId->get($id))->filter()->values();

        $html = $products->map(fn ($p) => '<li class="rv-product-card">'.view('components.product-card', ['product' => $p])->render().'</li>')->implode('');

        return response()->json(['html' => $html, 'count' => $products->count()]);
    }

    /** Build the reference-style welcome tabs (All / Ghee / Oils / Atta / Combos / Deal / Superfoods) + first-tab products. */
    protected function welcomeSection(array $config, int $count): array
    {
        $tabs = [];
        $sources = [];
        $raw = $config['tabs'] ?? null;

        if (is_array($raw) && count($raw)) {
            foreach (array_values($raw) as $item) {
                if (is_string($item)) { // legacy: plain category slug
                    $slug = trim($item);
                    $row = [
                        'title' => ucwords(str_replace('-', ' ', $slug)),
                        'key' => $slug,
                        'type' => 'category',
                        'value' => $slug,
                    ];
                } else {
                    $row = is_array($item) ? $item : [];
                }
                $sources[] = $row;
                $tabs[] = $this->welcomeTab($row, $count);
            }
        }

        if (! count($tabs)) {
            foreach ($this->rootCategories(7) as $cat) {
                $row = [
                    'title' => $cat->name,
                    'key' => 'tab-'.$cat->id,
                    'type' => 'category',
                    'value' => $cat->slug,
                ];
                $sources[] = $row;
                $tabs[] = $this->welcomeTab($row, $count);
            }
        }

        $first = $sources[0] ?? null;

        return [$tabs, $first ? $this->resolveTabProducts($first, $count) : collect()];
    }

    protected function welcomeTab(array $t, int $count): array
    {
        $title = $t['title'] ?? 'Tab';
        $type = $t['type'] ?? 'all';
        $key = \Illuminate\Support\Str::slug($t['key'] ?? $title);

        $active = $this->iconSrc($t['active_icon'] ?? null, $t['inactive_icon'] ?? null);
        $inactive = $this->iconSrc($t['inactive_icon'] ?? null);

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
            'see_all' => $this->tabSeeAllUrl($t),
            'active_icon' => $active,
            'inactive_icon' => $inactive,
            'fallback' => $t['fallback'] ?? null,
        ];
    }

    /** Resolve an icon path to a URL that is guaranteed to render on this host.
     *
     * SVG files in public are embedded as base64 data URIs — no extra HTTP
     * request, no ModSecurity / missing-file failures, always visible.
     */
    protected function iconSrc(?string $path, ?string $fallback = null): ?string
    {
        $leaf = 'data:image/svg+xml;base64,'.base64_encode(self::DEFAULT_ICON_SVG);

        foreach ([$path, $fallback] as $candidate) {
            if (empty($candidate)) {
                continue;
            }
            $uri = $this->iconDataUri($candidate);
            if ($uri !== null) {
                return $uri;
            }
            // A plain (non-uploaded) SVG we can't inline → use the leaf mark so
            // the tab never renders a broken <img> with its alt text showing.
            if (str_ends_with(strtolower((string) $candidate), '.svg')) {
                return $leaf;
            }
            return asset($candidate);
        }

        return $leaf;
    }

    /** Read an SVG file into a data URI, or null when it cannot be resolved to a local file. */
    protected function iconDataUri(?string $path): ?string
    {
        if (empty($path)) {
            return null;
        }
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return null;
        }
        if (! str_ends_with(strtolower($path), '.svg')) {
            return null;
        }

        $full = $path;
        if (str_starts_with($path, 'storage/')) {
            $full = public_path($path);
        } elseif (str_starts_with($path, 'images/')) {
            $full = public_path($path);
        } elseif (str_starts_with($path, '/')) {
            $full = public_path(ltrim($path, '/'));
        }

        if (! is_file($full)) {
            return null;
        }

        $raw = @file_get_contents($full);
        if ($raw === false || trim($raw) === '') {
            return null;
        }

        return 'data:image/svg+xml;base64,'.base64_encode($raw);
    }

    /** Destination of the reference 'See All' button for a menu tab. */
    protected function tabSeeAllUrl(array $t): string
    {
        $type = $t['type'] ?? 'all';

        if ($type === 'category' && ! empty($t['value'])) {
            return route('shop.category', $t['value']);
        }

        if (in_array($type, ['keyword', 'categories']) && (! empty($t['value']) || ! empty($t['values']))) {
            $term = $t['value'] ?? (($t['values'][0] ?? ''));
            return route('shop.search', ['q' => $term]);
        }

        return route('shop.categories');
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

        $fallback = $tab['fallback'] ?? null;
        if (is_array($fallback)) {
            if ($products->isEmpty()) {
                $products = $this->resolveTabProducts($fallback, $count);
            } elseif ($products->count() < $limit) {
                $exclude = $products->pluck('id')->all();
                $more = $this->resolveTabProducts($fallback, $limit - $products->count())
                    ->reject(fn ($p) => in_array($p->id, $exclude));
                $products = $products->merge($more);
            }
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

    /** Build the reference 'Product in Focus' section: an "All" first tab + keyword/category tabs. */
    protected function focusSection(string $key, array $config, int $count): array
    {
        $tabs = [];
        $sources = [];
        $raw = $config['tabs'] ?? null;
        $needle = str_contains($key, 'ghee') ? 'ghee' : 'oil';
        $catSlug = str_contains($key, 'ghee') ? 'ghee' : 'oil';
        $allTitle = str_contains($key, 'ghee') ? 'All Ghee' : 'All Oils';

        // Default "All" tab = whole category, shown first so the rail starts rich.
        $all = [
            'title' => $allTitle,
            'key' => 'all-'.$catSlug,
            'type' => 'category',
            'value' => $catSlug,
        ];
        $sources[] = $all;
        $tabs[] = $this->welcomeTab($all, $count);

        if (is_array($raw) && count($raw)) {
            foreach (array_values($raw) as $item) {
                $row = is_string($item)
                    ? ['title' => ucwords(str_replace('-', ' ', $item)), 'key' => $item, 'type' => 'keyword', 'value' => $item]
                    : (is_array($item) ? $item : []);
                if (empty($row['key'])) {
                    $row['key'] = \Illuminate\Support\Str::slug($row['title'] ?? 'tab');
                }
                if (empty($row['type'])) {
                    $row['type'] = 'keyword';
                }
                $sources[] = $row;
                $tabs[] = $this->welcomeTab($row, $count);
            }
        }

        $keywords = str_contains($key, 'ghee')
            ? ['ghee']
            : ['groundnut', 'mustard', 'sunflower', 'olive', 'coconut', 'sesame'];
        foreach ($keywords as $term) {
            $exists = collect($sources)->contains(fn ($s) => ($s['value'] ?? '') === $term || ($s['title'] ?? '') === ucwords($term));
            if ($exists) {
                continue;
            }
            $row = [
                'title' => ucwords($term),
                'key' => \Illuminate\Support\Str::slug($term),
                'type' => 'keyword',
                'value' => $term,
                'fallback' => ['type' => 'category', 'value' => $catSlug],
            ];
            $sources[] = $row;
            $tabs[] = $this->welcomeTab($row, $count);
        }

        $first = $sources[0] ?? null;

        return [$tabs, $first ? $this->resolveTabProducts($first, $count) : collect()];
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