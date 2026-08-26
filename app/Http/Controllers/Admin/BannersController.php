<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
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
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'show_text' => ['nullable', 'boolean'],
        ]);

        if ($request->hasFile('desktop_image')) {
            $data['desktop_image'] = $request->file('desktop_image')->store('banners', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');
        $data['show_text'] = $request->boolean('show_text');

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
            $data['desktop_image'] = $request->file('desktop_image')->store('banners', 'public');
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
}
