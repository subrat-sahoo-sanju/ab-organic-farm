<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Services\InventoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(protected InventoryService $inventory) {}

    public function index(): View
    {
        $filters = request()->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'category' => ['nullable', 'integer'],
            'status' => ['nullable', 'in:active,draft,out_of_stock'],
            'flag' => ['nullable', 'in:featured,best_seller,new_arrival'],
        ]);

        return view('admin.catalog.products', [
            'products' => Product::query()
                ->with(['primaryImage', 'defaultVariant.inventory', 'category'])
                ->when($filters['q'] ?? null, fn ($q, $v) => $q->where(fn ($w) => $w
                    ->where('name', 'like', "%{$v}%")->orWhere('sku', 'like', "%{$v}%")))
                ->when($filters['category'] ?? null, fn ($q, $v) => $q->where('category_id', $v))
                ->when($filters['status'] ?? null, fn ($q, $v) => $q->where('status', $v))
                ->when(($filters['flag'] ?? '') === 'featured', fn ($q) => $q->where('is_featured', true))
                ->when(($filters['flag'] ?? '') === 'best_seller', fn ($q) => $q->where('is_best_seller', true))
                ->when(($filters['flag'] ?? '') === 'new_arrival', fn ($q) => $q->where('is_new_arrival', true))
                ->latest()
                ->paginate(15)
                ->withQueryString(),
            'categories' => Category::orderBy('name')->get(['id', 'name']),
            'filters' => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.catalog.product-form', [
            'product' => new Product(),
            'categories' => Category::orderBy('sort_order')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);

        $product = DB::transaction(function () use ($request, $data) {
            $product = Product::create([
                ...$data,
                'uuid' => (string) Str::uuid(),
                'slug' => $this->uniqueSlug($data['name']),
                'sku' => $data['sku'],
                'published_at' => $data['status'] === 'active' ? now() : null,
            ]);

            foreach ($this->validatedVariants($request) as $i => $variant) {
                $v = $product->variants()->create([...$variant, 'is_default' => $i === 0, 'sort_order' => $i]);
                $this->inventory->ensureForVariant($v)->update([
                    'stock' => max(0, (int) ($variant['stock'] ?? 0)),
                    'low_stock_threshold' => (int) ($variant['low_stock_threshold'] ?? 10),
                ]);
            }

            if ($request->hasFile('images')) {
                app(ProductImageController::class)->saveUploads($request, $product);
            }

            return $product;
        });

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product created — you can manage images and variants below.');
    }

    public function edit(Product $product): View
    {
        $product->load(['variants.inventory', 'images', 'category']);

        return view('admin.catalog.product-form', [
            'product' => $product,
            'categories' => Category::orderBy('sort_order')->get(),
            'brands' => Brand::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product->id);
        unset($data['sku']); // SKU immutable after creation

        $product->update([
            ...$data,
            'published_at' => $product->published_at ?? ($data['status'] === 'active' ? now() : null),
        ]);

        // Sync variants (simple replace-by-id strategy from form arrays)
        foreach ($this->validatedVariants($request, $product) as $payload) {
            $variant = $product->variants()->updateOrCreate(
                ['id' => $payload['id'] ?? null],
                collect($payload)->except('id', 'stock')->all()
            );

            if (array_key_exists('stock', $payload)) {
                $inventory = $this->inventory->ensureForVariant($variant);
                $delta = (int) $payload['stock'] - $inventory->stock;
                if ($delta !== 0) {
                    $this->inventory->adjust(
                        $inventory,
                        $delta,
                        \App\Enums\InventoryTxnType::Adjustment,
                        'Adjusted via product edit'
                    );
                }
            }
        }

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product moved to trash.');
    }

    public function trashed(): View
    {
        return view('admin.catalog.products-trashed', [
            'products' => Product::onlyTrashed()->with('category')->latest('deleted_at')->paginate(15),
        ]);
    }

    public function restore(int $id): RedirectResponse
    {
        Product::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Product restored.');
    }

    public function forceDelete(int $id): RedirectResponse
    {
        Product::onlyTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Product permanently deleted.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:190'],
            'sku' => [$ignoreId ? 'nullable' : 'required', 'string', 'max:64', 'unique:products,sku'.($ignoreId ? ','.$ignoreId : '')],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['nullable', 'exists:brands,id'],
            'short_description' => ['nullable', 'string', 'max:500'],
            'description' => ['nullable', 'string'],
            'ingredients' => ['nullable', 'string'],
            'benefits' => ['nullable', 'string'],
            'usage_instructions' => ['nullable', 'string'],
            'storage_instructions' => ['nullable', 'string'],
            'origin' => ['nullable', 'string', 'max:190'],
            'farmer_source' => ['nullable', 'string', 'max:190'],
            'certification' => ['nullable', 'string', 'max:190'],
            'is_organic' => ['nullable', 'boolean'],
            'badge_label' => ['nullable', 'string', 'max:64'],
            'cost_price' => ['nullable', 'numeric', 'min:0'],
            'regular_price' => ['required', 'numeric', 'min:0'],
            'sale_price' => ['nullable', 'numeric', 'min:0'],
            'unit_label' => ['nullable', 'string', 'max:32'],
            'promo_note' => ['nullable', 'string', 'max:190'],
            'status' => ['required', 'in:active,draft,out_of_stock'],
            'is_featured' => ['nullable', 'boolean'],
            'is_best_seller' => ['nullable', 'boolean'],
            'is_new_arrival' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        return $request->validate($rules);
    }

    protected function validatedVariants(Request $request, ?Product $existing = null): array
    {
        return $request->validate([
            'variants' => ['required', 'array', 'min:1'],
            'variants.*.id' => ['nullable', 'integer'],
            'variants.*.sku' => ['required', 'string', 'max:64'],
            'variants.*.name' => ['required', 'string', 'max:64'],
            'variants.*.price' => ['required', 'numeric', 'min:0'],
            'variants.*.sale_price' => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock' => ['nullable', 'integer', 'min:0'],
            'variants.*.is_active' => ['nullable', 'boolean'],
        ])['variants'];
    }

    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: Str::random(8);
        $slug = $base;
        $i = 1;
        while (Product::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }
}
