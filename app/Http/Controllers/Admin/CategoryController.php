<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
            $data['image_path'] = $this->storedImage($request);
        }

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
            $data['image_path'] = $this->storedImage($request);
        }

        // Prevent self/nested parent loops
        if ($data['parent_id'] && (int) $data['parent_id'] === $category->id) {
            unset($data['parent_id']);
        }

        $category->update($data);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        abort_if($category->children()->exists(), 422, 'Move or delete sub-categories first.');
        abort_if($category->products()->exists(), 422, 'Category still has products.');

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }

    public function restore(int $id): RedirectResponse
    {
        Category::onlyTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Category restored.');
    }

    protected function validated(Request $request, ?int $ignoreId = null): array
    {
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

    /** Auto-adjust (center-crop) the category image to the recommended square slot. */
    protected function storedImage(Request $request): ?string
    {
        return app(ImageUtility::class)->processUpload($request->file('image'), 800, 800, 'categories');
    }
}
