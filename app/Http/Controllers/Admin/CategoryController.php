<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        return view('admin.catalog.categories', [
            'categories' => Category::with('children.children')
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get(),
            'allCategories' => Category::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storedImage($request, 'image', 800, 800);
        }
        if ($request->hasFile('card_image_file')) {
            $data['card_image'] = $this->storedImage($request, 'card_image_file', 800, 800);
        }
        if ($request->hasFile('banner_image_file')) {
            $data['banner_image'] = $this->storedImage($request, 'banner_image_file', 2400, 700);
        }
        if ($request->hasFile('brand_logo_file')) {
            $data['brand_logo'] = $this->storedImage($request, 'brand_logo_file', 400, 120);
        }
        $data['sections'] = $this->normalizeSections($request);
        $data['banner_images'] = $this->handleBannerImages($request);

        Category::create($data);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $this->validated($request, $category->id);
        $data['slug'] = $category->slug;

        if ($request->filled('name') && trim($request->name) !== $category->name) {
            $data['slug'] = $this->uniqueSlug($request->name, $category->id);
        }

        if ($request->hasFile('image')) {
            $data['image_path'] = $this->storedImage($request, 'image', 800, 800);
        }
        if ($request->hasFile('card_image_file')) {
            $data['card_image'] = $this->storedImage($request, 'card_image_file', 800, 800);
        }
        if ($request->hasFile('banner_image_file')) {
            $data['banner_image'] = $this->storedImage($request, 'banner_image_file', 2400, 700);
        }
        if ($request->hasFile('brand_logo_file')) {
            $data['brand_logo'] = $this->storedImage($request, 'brand_logo_file', 400, 120);
        }
        $data['sections'] = $this->normalizeSections($request);
        $data['banner_images'] = $this->handleBannerImages($request, $category->banner_images);

        // Prevent self/nested parent loops
        if ($data['parent_id'] && (int) $data['parent_id'] === $category->id) {
            unset($data['parent_id']);
        }

        $category->update($data);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        DB::transaction(function () use ($category) {
            $parentId = $category->parent_id;
            $firstChildId = $category->children()->value('id');

            // Re-parent sub-categories so they (and their products) stay live.
            Category::where('parent_id', $category->id)->update(['parent_id' => $parentId]);

            // Reassign direct products so none become orphaned.
            if ($category->products()->exists()) {
                $destinationId = $parentId ?? $firstChildId;
                if ($destinationId) {
                    $category->products()->update(['category_id' => $destinationId]);
                }
            }

            $category->delete();
        });

        return back()->with('success', 'Category deleted. Sub-categories and products were kept.');
    }

    public function restore(int $id): RedirectResponse
    {
        Category::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Category restored.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
        // The admin form submits sections/banner_images as JSON strings; decode to arrays
        // before validating so the array rules pass and downstream handlers get arrays.
        foreach (['sections', 'banner_images'] as $jsonField) {
            if ($request->has($jsonField) && is_string($input = $request->input($jsonField))) {
                $decoded = json_decode($input, true);
                $request->merge([$jsonField => is_array($decoded) ? $decoded : []]);
            }
        }

        return $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'parent_id' => ['nullable', 'integer', 'exists:categories,id'],
            'description' => ['nullable', 'string', 'max:2000'],
            'icon' => ['nullable', 'string', 'max:64'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'seo_title' => ['nullable', 'string', 'max:190'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'card_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'banner_heading' => ['nullable', 'string', 'max:190'],
            'banner_subheading' => ['nullable', 'string', 'max:300'],
            'banner_image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'banner_cta_text' => ['nullable', 'string', 'max:80'],
            'banner_cta_url' => ['nullable', 'string', 'max:500'],
            'banner_bg_color' => ['nullable', 'string', 'max:20'],
            'brand_logo_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'brand_name' => ['nullable', 'string', 'max:120'],
            'banner_images_files' => ['nullable', 'array'],
            'banner_images_files.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'banner_images' => ['nullable', 'array'],
            'sections' => ['nullable', 'array'],
            'sections.*.type' => ['required_with:sections', 'string', 'in:welcome,featured_products,trust_badges,promo_banner,cross_sell'],
            'sections.*.title' => ['nullable', 'string', 'max:190'],
            'sections.*.subtitle' => ['nullable', 'string', 'max:300'],
            'sections.*.visible' => ['nullable', 'boolean'],
            'sections.*.config' => ['nullable', 'array'],
        ]);
    }

    protected function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: Str::random(8);
        $slug = $base;
        $i = 1;
        while (Category::where('slug', $slug)->when($ignoreId, fn ($q) => $q->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.(++$i);
        }

        return $slug;
    }

    /** Auto-adjust (center-crop) the uploaded image to the recommended dimensions. */
    protected function storedImage(Request $request, string $field, int $w, int $h): ?string
    {
        return app(ImageUtility::class)->processUpload($request->file($field), $w, $h, 'categories');
    }

    /**
     * Decode and normalize the sections JSON from the form.
     * Converts product_ids_str to product_ids array for storage.
     */
    protected function normalizeSections(Request $request): ?array
    {
        $raw = $request->input('sections');
        if (!$raw) {
            return null;
        }

        $sections = is_string($raw) ? json_decode($raw, true) : $raw;
        if (!is_array($sections)) {
            return null;
        }

        return array_map(function ($sec) {
            // Convert product_ids_str comma-separated string to array
            if (isset($sec['product_ids_str'])) {
                $sec['config'] = $sec['config'] ?? [];
                $sec['config']['product_ids'] = array_filter(array_map('intval',
                    explode(',', $sec['product_ids_str'])
                ));
                unset($sec['product_ids_str']);
            }
            // Normalize welcome-section tabs (nested product_ids pulses stay as strings
            // in the form and are converted here to arrays).
            if (($sec['type'] ?? null) === 'welcome') {
                $sec['config'] = $sec['config'] ?? [];
                if (isset($sec['config']['tabs']) && is_array($sec['config']['tabs'])) {
                    foreach ($sec['config']['tabs'] as $i => $tab) {
                        if (isset($tab['product_ids_str'])) {
                            $sec['config']['tabs'][$i]['product_ids'] = array_values(array_filter(array_map('intval',
                                preg_split('/[\s,]+/', (string) $tab['product_ids_str'])
                            )));
                            unset($sec['config']['tabs'][$i]['product_ids_str']);
                        }
                    }
                }
            }
            // Ensure config exists
            $sec['config'] = $sec['config'] ?? [];
            $sec['visible'] = $sec['visible'] ?? true;
            return $sec;
        }, $sections);
    }

    /**
     * Handle multiple banner image uploads for the hero carousel.
     */
    protected function handleBannerImages(Request $request, ?array $existing = null): ?array
    {
        $images = $existing ?? [];
        $keptUrls = $request->input('banner_images', []);

        // Remove deleted images from storage
        if ($existing) {
            foreach ($existing as $img) {
                if (!in_array($img, $keptUrls)) {
                    $path = public_path('storage/' . $img);
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }
            }
        }

        // Keep only images that weren't deleted
        $images = array_values(array_filter($images, fn($img) => in_array($img, $keptUrls)));

        // Add new uploads
        if ($request->hasFile('banner_images_files')) {
            foreach ($request->file('banner_images_files') as $file) {
                $uploaded = app(ImageUtility::class)->processUpload($file, 2400, 700, 'categories');
                if ($uploaded) {
                    $images[] = $uploaded;
                }
            }
        }

        return !empty($images) ? $images : null;
    }
}
