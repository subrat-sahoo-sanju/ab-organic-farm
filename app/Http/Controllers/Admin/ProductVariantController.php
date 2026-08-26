<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;

class ProductVariantController extends Controller
{
    public function makeDefault(ProductVariant $variant): RedirectResponse
    {
        abort_unless($variant->is_active, 422, 'Activate the variant first.');

        $variant->product->variants()->update(['is_default' => false]);
        $variant->forceFill(['is_default' => true])->save();

        return back()->with('success', "Default variant set to {$variant->name}.");
    }
}
