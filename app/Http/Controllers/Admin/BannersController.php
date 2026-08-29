<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageUtility;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BannersController extends Controller
{
    public function index(): View
    {
        return view('admin.marketing.banners', [
            'banners' => Banner::orderBy('sort_order')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'button_text' => ['nullable', 'string', 'max:64'],
            'button_url' => ['nullable', 'string', 'max:190'],
            'desktop_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'placement' => ['nullable', 'in:hero,strip,category_top,promotional'],
            'width' => ['nullable', 'integer', 'min:1', 'max:4000'],
            'height' => ['nullable', 'integer', 'min:1', 'max:4000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'show_text' => ['nullable', 'boolean'],
        ]);

        $placement = $data['placement'] ?? 'hero';
        if ($request->hasFile('desktop_image')) {
            $data['desktop_image'] = $this->storedImage($request, 'desktop_image', $placement);
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['show_text'] = $request->boolean('show_text');
        $data['width'] = $data['width'] ?: null;
        $data['height'] = $data['height'] ?: null;

        Banner::create($data);

        return back()->with('success', 'Banner added.');
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'button_text' => ['nullable', 'string', 'max:64'],
            'button_url' => ['nullable', 'string', 'max:190'],
            'desktop_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'placement' => ['nullable', 'in:hero,strip,category_top,promotional'],
            'width' => ['nullable', 'integer', 'min:1', 'max:4000'],
            'height' => ['nullable', 'integer', 'min:1', 'max:4000'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'show_text' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['show_text'] = $request->boolean('show_text');
        $data['width'] = $data['width'] ?: null;
        $data['height'] = $data['height'] ?: null;

        if ($request->hasFile('desktop_image')) {
            $old = str_replace('storage/', '', $banner->desktop_image ?? '');
            if ($old && \Storage::disk('public')->exists($old)) {
                \Storage::disk('public')->delete($old);
            }
            $placement = $banner->placement ?? ($data['placement'] ?? 'hero');
            $data['desktop_image'] = $this->storedImage($request, 'desktop_image', $placement);
        } else {
            unset($data['desktop_image']);
        }

        $banner->update($data);

        return back()->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $old = str_replace('storage/', '', $banner->desktop_image ?? '');
        if ($old && \Storage::disk('public')->exists($old)) {
            \Storage::disk('public')->delete($old);
        }
        $banner->delete();
        return back()->with('success', 'Banner deleted.');
    }

    public function toggle(Banner $banner): RedirectResponse
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return back()->with('success', $banner->is_active ? 'Banner enabled.' : 'Banner disabled.');
    }

    /**
     * Store a banner image.
     *
     * Every upload is kept as the FULL original image (never cropped) so the
     * storefront always shows the complete picture and auto-adjusts the banner
     * section to the image — no manual width/height needed. When the admin
     * provides BOTH width and height the upload is centre-cropped to those
     * exact dimensions instead (optional override).
     */
    protected function storedImage(Request $request, string $field, string $placement): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $w = (int) $request->input('width');
        $h = (int) $request->input('height');

        $canvas = app(ImageUtility::class);

        if ($w > 0 && $h > 0) {
            return $canvas->processUpload($request->file($field), $w, $h, 'banners');
        }

        return $canvas->storeOriginal($request->file($field), 'banners');
    }
}
