<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Services\ImageUtility;
use Illuminate\Http\JsonResponse;
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

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'button_text' => ['nullable', 'string', 'max:64'],
            'button_url' => ['nullable', 'string', 'max:190'],
            'desktop_image' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'placement' => ['nullable', 'in:hero,strip,category_top,promotional'],
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
        [$data['width'], $data['height']] = self::PLACEMENT_SIZES[$placement] ?? self::PLACEMENT_SIZES['promotional'];

        Banner::create($data);

        return $this->respond($request, 'Banner added.');
    }

    public function update(Request $request, Banner $banner): RedirectResponse|JsonResponse
    {
        $data = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:120'],
            'subtitle' => ['nullable', 'string', 'max:190'],
            'button_text' => ['nullable', 'string', 'max:64'],
            'button_url' => ['nullable', 'string', 'max:190'],
            'desktop_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:4096'],
            'placement' => ['nullable', 'in:hero,strip,category_top,promotional'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'show_text' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['show_text'] = $request->boolean('show_text');

        if ($request->hasFile('desktop_image')) {
            $old = str_replace('storage/', '', $banner->desktop_image ?? '');
            if ($old && \Storage::disk('public')->exists($old)) {
                \Storage::disk('public')->delete($old);
            }
            $placement = $banner->placement ?? ($data['placement'] ?? 'hero');
            $data['desktop_image'] = $this->storedImage($request, 'desktop_image', $placement);
        } else {
            unset($data['desktop_image']);
            $placement = $data['placement'] ?? $banner->placement ?? 'hero';
        }
        [$data['width'], $data['height']] = self::PLACEMENT_SIZES[$placement] ?? self::PLACEMENT_SIZES['promotional'];

        $banner->update($data);

        return $this->respond($request, 'Banner updated.');
    }

    public function destroy(Request $request, Banner $banner): RedirectResponse|JsonResponse
    {
        $old = str_replace('storage/', '', $banner->desktop_image ?? '');
        if ($old && \Storage::disk('public')->exists($old)) {
            \Storage::disk('public')->delete($old);
        }
        $banner->delete();
        return $this->respond($request, 'Banner deleted.');
    }

    public function toggle(Request $request, Banner $banner): RedirectResponse|JsonResponse
    {
        $banner->update(['is_active' => !$banner->is_active]);
        return $this->respond($request, $banner->is_active ? 'Banner enabled.' : 'Banner disabled.');
    }

    /**
     * Return an AJAX payload (fresh grid HTML + count) for real-time updates,
     * or fall back to the classic redirect + flash for non-JS/regular posts.
     */
    protected function respond(Request $request, string $message): RedirectResponse|JsonResponse
    {
        if ($request->ajax() || $request->wantsJson()) {
            $banners = Banner::orderBy('sort_order')->get();
            return response()->json([
                'ok' => true,
                'message' => $message,
                'count' => $banners->count(),
                'grid' => view('admin.marketing.banners._grid', ['banners' => $banners])->render(),
            ]);
        }
        return back()->with('success', $message);
    }

    /**
     * Required image sizes per placement (width × height). Used for both
     * auto-cropping and the admin-side size guide.
     */
    public const PLACEMENT_SIZES = [
        'hero'         => [1600, 500],
        'strip'        => [1200, 150],
        'category_top' => [1200, 220],
        'promotional'  => [1200, 400],
    ];

    /**
     * Store a banner image. Every upload is centre-cropped (cover) to the
     * required size for the chosen placement, so the banner always renders
     * perfectly on the storefront without the admin typing dimensions.
     */
    protected function storedImage(Request $request, string $field, string $placement): ?string
    {
        if (! $request->hasFile($field)) {
            return null;
        }

        $canvas = app(ImageUtility::class);
        [$w, $h] = self::PLACEMENT_SIZES[$placement] ?? self::PLACEMENT_SIZES['promotional'];

        return $canvas->processUpload($request->file($field), $w, $h, 'banners');
    }
}