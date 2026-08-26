<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $s = app(SettingsService::class);

        $defaults = [
            'store.name' => 'AB Organic Farm',
            'store.tagline' => 'Good Food. Naturally Better.',
            'store.phone' => '+91 94370 00000',
            'store.email' => 'hello@verdurafarms.in',
            'store.address' => 'Plot 12, Green Valley Road, Bhubaneswar, Odisha 751001',
            'delivery.standard_charge' => '49',
            'delivery.free_above' => '999',
            'delivery.min_order' => '199',
            'order.auto_confirm' => '0',
            'order.cancellation_window_hours' => '24',
            'cod.enabled' => '1',
            'cod.max_order_value' => '10000',
            'cod.instructions' => 'Please keep exact change ready. Our delivery partner will collect the cash at your doorstep.',
            'seo.title' => 'AB Organic Farm — Organic Products Delivered | Farm to Home',
            'seo.description' => 'Shop certified organic fruits, vegetables, grains, pulses, spices and natural personal care. Fresh from farms to your doorstep. Cash on Delivery available.',
            'social.facebook' => 'https://facebook.com/verdurafarms',
            'social.instagram' => 'https://instagram.com/verdurafarms',
            'social.youtube' => '',
            'social.whatsapp' => '+91 94370 00000',
        ];

        foreach ($defaults as $key => $value) {
            $s->set($key, $value);
        }
    }
}
