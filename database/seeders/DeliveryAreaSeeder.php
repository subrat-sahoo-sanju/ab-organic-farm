<?php

namespace Database\Seeders;

use App\Models\DeliveryArea;
use Illuminate\Database\Seeder;

class DeliveryAreaSeeder extends Seeder
{
    public function run(): void
    {
        $areas = [
            ['751001', 'Bhubaneswar', 'Odisha', 'Old Town', 1],
            ['751002', 'Bhubaneswar', 'Odisha', 'Laxmi Sagar', 1],
            ['751007', 'Bhubaneswar', 'Odisha', 'Saheed Nagar', 0],
            ['751009', 'Bhubaneswar', 'Odisha', 'Patia', 0],
            ['751024', 'Bhubaneswar', 'Odisha', 'Chandrasekharpur', 1],
            ['752101', 'Cuttack', 'Odisha', null, 2],
        ];

        foreach ($areas as [$pin, $city, $state, $area, $eta]) {
            DeliveryArea::updateOrCreate(
                ['pincode' => $pin, 'area' => $area],
                [
                    'city' => $city,
                    'state' => $state,
                    'is_serviceable' => true,
                    'delivery_charge' => $pin === '752101' ? 79 : 49,
                    'eta_days' => $eta,
                    'cod_available' => true,
                ]
            );
        }

        // One unserviceable pincode for demoing the "no delivery" state
        DeliveryArea::create([
            'pincode' => '110001',
            'city' => 'New Delhi',
            'state' => 'Delhi',
            'is_serviceable' => false,
            'cod_available' => false,
        ]);
    }
}
