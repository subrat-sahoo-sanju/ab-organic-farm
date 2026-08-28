<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Services\ImageUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BrandController extends Controller
{
    public function index(): View
    {
        return view('admin.catalog.brands', [
            'brands' => Brand::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo_path'] = $this->storedLogo($request);
        }
        unset($data['logo']);

        Brand::create([
            ...$data,
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
        ]);

        return back()->with('success', 'Brand added.');
    }

    public function update(Request $request, Brand $brand): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:120'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('logo')) {
            $old = str_replace('storage/', '', $brand->logo_path ?? '');
            if ($old && \Storage::disk('public')->exists($old)) {
                \Storage::disk('public')->delete($old);
            }
            $data['logo_path'] = $this->storedLogo($request);
        }
        unset($data['logo']);

        $brand->update($data);

        return back()->with('success', 'Brand updated.');
    }

    public function destroy(Brand $brand): RedirectResponse
    {
        abort_if($brand->products()->exists(), 422, 'Brand has products.');

        $old = str_replace('storage/', '', $brand->logo_path ?? '');
        if ($old && \Storage::disk('public')->exists($old)) {
            \Storage::disk('public')->delete($old);
        }
        $brand->delete();

        return back()->with('success', 'Brand deleted.');
    }

    /** Auto-adjust (center-crop) the brand logo to the recommended square slot. */
    protected function storedLogo(Request $request): ?string
    {
        return app(ImageUtility::class)->processUpload($request->file('logo'), 300, 300, 'brands');
    }
}
