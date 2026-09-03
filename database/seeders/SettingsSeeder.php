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

            // SEO / social extras
            'seo.keywords' => 'organic food, atta, ghee, natural oils, AB Organic',
            'og.title' => 'AB Organic Farm — Organic Products Delivered | Farm to Home',
            'og.description' => 'Shop certified organic fruits, vegetables, grains, pulses, spices and natural personal care. Fresh from farms to your doorstep.',
            'social.whatsapp' => '+91 94370 00000',

            // COD & payments remaining
            'cod.min_order_value' => '0',
            'cod.delivery_charges' => '49',
            'cod.free_delivery_above' => '499',
            'cod.advance_percent' => '0',

            // Inventory
            'inventory.low_stock_threshold' => '5',
            'inventory.email_alerts' => '1',

            // Notifications
            'notify.admin_email' => 'hello@verdurafarms.in',
            'notify.sms' => '0',
            'notify.whatsapp' => '1',

            // Homepage content
            'home.search_placeholder' => 'Search products, e.g. ghee',
            'home.delivery_charge_text' => 'Free delivery above ₹499',
            'home.brand_title' => 'Shop by Brand',
            'home.brand_subtitle' => 'Explore a curated range from trusted brands.',
            'home.featured_title' => 'Featured Products',
            'home.featured_subtitle' => 'Hand-picked organic favourites our customers love.',
            'home.best_title' => 'Best Sellers',
            'home.best_subtitle' => 'The products everyone keeps coming back for.',
            'home.new_title' => 'New Arrivals',
            'home.new_subtitle' => 'Fresh from the farm and just landed in store.',
            'home.why_title' => 'Why Choose Us',
            'home.testimonial_title' => 'What Our Customers Say',
            'home.cta_title' => 'Go Organic. Go Fresh. Go Fast.',
            'home.cta_subtitle' => 'Join thousands of families who trust AB Organic Farm for their daily groceries.',
            'home.cta_button' => 'Start Shopping',
            'home.cta_link' => '/categories/all',

            // Footer extras
            'footer.tagline' => 'Good Food. Naturally Better.',
            'footer.address' => 'Plot 12, Green Valley Road, Bhubaneswar, Odisha 751001',
            'store.contact_link' => '#',

            // Display extras
            'display.app_download_heading' => 'Unlock 17% OFF exclusively on the App',
            'display.app_download_sub' => 'Get the AB Organic Farm app today.',
            'display.app_download_url2' => '#',
            'display.app_store_url' => '#',
            'display.app_download_url' => '#',
            'display.rewards_enabled' => '1',
            'display.rewards_mainline' => 'Earn rewards on every order!',
            'display.rewards_coins' => '0',
            'display.rewards_subline' => 'Your rewards await',
            'display.whatsapp_number' => '919999999999',
            'display.whatsapp_message' => 'Hi! I have a question about your products.',
        ];

        $defaults['display.nav_menu'] = json_encode([
            ['label' => 'All Products', 'icon' => 'nav-all', 'url' => '/categories/all', 'highlight' => false, 'children' => []],
            ['label' => 'Ghee', 'icon' => 'nav-ghee', 'url' => '/categories/ghee', 'highlight' => false, 'children' => [
                ['label' => 'Jar Type', 'url' => '/categories/ghee-jar-type'],
                ['label' => 'Packed Type', 'url' => '/categories/ghee-packed-type'],
                ['label' => 'Multitype Ghee', 'url' => '/categories/ghee-multitype'],
            ]],
            ['label' => 'Oil', 'icon' => 'nav-oils', 'url' => '/categories/oil', 'highlight' => false, 'children' => []],
            ['label' => 'Atta', 'icon' => 'nav-atta', 'url' => '/categories/atta', 'highlight' => false, 'children' => []],
            ['label' => 'Hot Deals', 'icon' => 'nav-deal', 'url' => '/search?q=deal', 'highlight' => true, 'children' => []],
            ['label' => 'Shop', 'icon' => 'nav-category', 'url' => '/categories', 'highlight' => false, 'children' => [
                ['label' => 'Ghee', 'url' => '/categories/ghee'],
                ['label' => 'Oil', 'url' => '/categories/oil'],
                ['label' => 'Atta', 'url' => '/categories/atta'],
            ]],
            ['label' => 'Healthy Combo', 'icon' => 'nav-combos', 'url' => '/search?q=combo', 'highlight' => false, 'children' => []],
        ], JSON_UNESCAPED_SLASHES);

        foreach ($defaults as $key => $value) {
            $s->set($key, $value);
        }
    }
}
