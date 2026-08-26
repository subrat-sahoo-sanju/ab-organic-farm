<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;

class CouponsController extends Controller
{
    public function index()
    {
        return view('admin.marketing.coupons', [
            'coupons' => Coupon::withCount(['usages', 'products', 'categories'])->latest()->paginate(20),
        ]);
    }

    public function store(\Illuminate\Http\Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:32', 'unique:coupons,code'],
            'description' => ['nullable', 'string', 'max:190'],
            'discount_type' => ['required', 'in:percentage,fixed'],
            'discount_value' => ['required', 'numeric', 'min:0.01'],
            'min_cart_value' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'uses_total' => ['nullable', 'integer', 'min:0'],
            'uses_per_user' => ['nullable', 'integer', 'min:0'],
            'valid_from' => ['required', 'date'],
            'valid_until' => ['required', 'date', 'after:valid_from'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'category_ids' => ['nullable', 'array'],
        ]);

        $coupon = Coupon::create(collect($data)->except(['product_ids', 'category_ids'])->all());

        if (! empty($data['product_ids'])) {
            $coupon->products()->sync($data['product_ids']);
        }
        if (! empty($data['category_ids'])) {
            $coupon->categories()->sync($data['category_ids']);
        }

        return back()->with('success', 'Coupon created.');
    }

    public function update(\Illuminate\Http\Request $request, Coupon $coupon): RedirectResponse
    {
        $data = $request->validate([
            'description' => ['nullable', 'string', 'max:190'],
            'discount_type' => ['sometimes', 'required', 'in:percentage,fixed'],
            'discount_value' => ['sometimes', 'required', 'numeric', 'min:0.01'],
            'min_cart_value' => ['nullable', 'numeric', 'min:0'],
            'max_discount_amount' => ['nullable', 'numeric', 'min:0'],
            'uses_total' => ['nullable', 'integer', 'min:0'],
            'uses_per_user' => ['nullable', 'integer', 'min:0'],
            'valid_from' => ['sometimes', 'required', 'date'],
            'valid_until' => ['sometimes', 'required', 'date'],
            'is_active' => ['nullable', 'boolean'],
            'product_ids' => ['nullable', 'array'],
            'category_ids' => ['nullable', 'array'],
        ]);

        $coupon->update(collect($data)->except(['product_ids', 'category_ids'])->all());

        if (array_key_exists('product_ids', $data)) {
            $coupon->products()->sync($data['product_ids'] ?? []);
        }
        if (array_key_exists('category_ids', $data)) {
            $coupon->categories()->sync($data['category_ids'] ?? []);
        }

        return back()->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        $coupon->delete();
        return back()->with('success', 'Coupon deleted.');
    }
}
