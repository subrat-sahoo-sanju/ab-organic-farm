<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\DeliveryArea;
use App\Services\CartService;
use App\Services\PricingService;
use Illuminate\View\View;

class DeliveryAreaController extends Controller
{
    public function check(string $pincode)
    {
        request()->validate(['pincode' => 'regex:/^\d{6}$/']);

        $area = DeliveryArea::where('pincode', $pincode)->first();

        if (! $area || ! $area->is_serviceable) {
            return response()->json([
                'serviceable' => false,
                'message' => "Sorry, we currently don't deliver to {$pincode}.",
            ]);
        }

        return response()->json([
            'serviceable' => true,
            'city' => $area->city,
            'eta_days' => (int) $area->eta_days,
            'cod_available' => $area->cod_available,
            'message' => $area->eta_days <= 0 ? 'Delivery available today!' : "Delivery available — usually within {$area->eta_days} day(s).",
        ]);
    }
}
