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
            'delivery.free_above' => '499',
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
            'display.trust_pills' => json_encode([
                ['text' => '100% Certified Organic', 'icon' => 'shield-check'],
                ['text' => 'Lab Tested', 'icon' => 'flask-conical'],
                ['text' => 'Farm to Table', 'icon' => 'truck'],
            ], JSON_UNESCAPED_SLASHES),
            'footer.company_name' => 'AB Organic Farm',
            'footer.copyright' => 'AB Organic Farm Pvt. Ltd.',
            'footer.newsletter_heading' => 'Stay in the loop',
            'footer.newsletter_sub' => 'Fresh offers & farm stories. No spam.',
            'display.whatsapp_name' => 'AB Organic Farm',
            'display.whatsapp_greeting' => 'Hi there! How can we help you today?',
        ];

        $defaults['display.nav_menu'] = json_encode([
            ['label' => 'All Products', 'icon' => 'nav-all', 'url' => '/categories/all', 'highlight' => false, 'children' => []],
            ['label' => 'A2 Ghee', 'icon' => 'nav-ghee', 'url' => '/categories/oils-ghee', 'highlight' => false, 'children' => []],
            ['label' => 'Wood-Pressed Oils', 'icon' => 'nav-oils', 'url' => '/categories/oils-ghee', 'highlight' => false, 'children' => []],
            ['label' => 'Atta', 'icon' => 'nav-atta', 'url' => '/categories/atta-flours', 'highlight' => false, 'children' => []],
            ['label' => 'Hot Deals', 'icon' => 'nav-deal', 'url' => '/search?q=deal', 'highlight' => true, 'children' => []],
            ['label' => 'Shop', 'icon' => 'nav-category', 'url' => '/categories/all', 'highlight' => false, 'children' => [
                ['label' => 'Superfoods', 'url' => '/search?q=superfood'],
                ['label' => 'Healthy Gifting', 'url' => '/categories/all'],
                ['label' => 'Shop Under ₹499', 'url' => '/search?q=under 499'],
                ['label' => 'Shop Under ₹999', 'url' => '/search?q=under 999'],
            ]],
            ['label' => 'Healthy Combo', 'icon' => 'nav-combos', 'url' => '/search?q=combo', 'highlight' => false, 'children' => []],
        ], JSON_UNESCAPED_SLASHES);

        foreach ($defaults as $key => $value) {
            $s->set($key, $value);
        }
    }
}
