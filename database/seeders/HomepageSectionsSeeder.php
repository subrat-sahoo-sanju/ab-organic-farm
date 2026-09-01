<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            ['key' => 'hero', 'title' => 'Hero banner', 'subtitle' => 'Grab attention with your flagship offers', 'sort_order' => 10, 'config' => null],
            ['key' => 'trust_badges', 'title' => 'Why shop with us', 'subtitle' => 'Trust signals strip', 'sort_order' => 20, 'config' => [
                'product_count' => null,
                'items' => [
                    ['icon' => 'leaf', 'title' => '100% Organic', 'text' => 'Certified organic with zero pesticides & chemicals'],
                    ['icon' => 'truck', 'title' => '10-Min Delivery', 'text' => 'Lightning-fast delivery of fresh organic produce'],
                    ['icon' => 'hand_coins', 'title' => 'Direct from Farms', 'text' => 'Fair prices to farmers, fresher produce for you'],
                    ['icon' => 'shield_check', 'title' => 'Lab Tested', 'text' => 'Every product undergoes rigorous quality testing'],
                ],
            ]],
            ['key' => 'categories', 'title' => 'Shop by Category', 'subtitle' => 'Explore our farm-fresh collections', 'sort_order' => 30, 'config' => ['product_count' => 8]],
            ['key' => 'welcome', 'title' => 'Welcome to AB Organic Farm!', 'subtitle' => "You're one step closer to purity. Tap a category to explore.", 'sort_order' => 40, 'config' => ['product_count' => 12, 'tabs' => null]],
            ['key' => 'deals', 'title' => "Today's Hot Deals", 'subtitle' => 'Best prices in town, straight from the farm', 'sort_order' => 50, 'config' => ['product_count' => 8]],
            ['key' => 'trending', 'title' => 'Trending Now', 'subtitle' => 'What our community is loving right now', 'sort_order' => 60, 'config' => ['product_count' => 10]],
            ['key' => 'focus_ghee', 'title' => "Product in Focus: A2 Desi Ghee", 'subtitle' => 'Pure bilona-churned goodness', 'sort_order' => 70, 'config' => ['product_count' => 8, 'tabs' => null]],
            ['key' => 'best_sellers', 'title' => 'Best Sellers', 'subtitle' => 'Trusted by thousands of households', 'sort_order' => 80, 'config' => ['product_count' => 10]],
            ['key' => 'focus_oils', 'title' => 'Product in Focus: Cold-Pressed Oils', 'subtitle' => 'Wood-pressed for maximum nutrition', 'sort_order' => 90, 'config' => ['product_count' => 8, 'tabs' => null]],
            ['key' => 'quality', 'title' => 'Only Perfect Makes The Cut', 'subtitle' => 'Every jar is crafted, tested and hand-picked', 'sort_order' => 100, 'config' => ['product_count' => 4]],
            ['key' => 'new_arrivals', 'title' => 'New Arrivals', 'subtitle' => 'Just stocked, fresh off the farm', 'sort_order' => 110, 'config' => ['product_count' => 10]],
            ['key' => 'recommended', 'title' => 'Recommended For You', 'subtitle' => 'Hand-picked organic favourites', 'sort_order' => 120, 'config' => ['product_count' => 10]],
            ['key' => 'organic_picks', 'title' => 'Organic Picks', 'subtitle' => 'Certified pure, naturally better', 'sort_order' => 130, 'config' => ['product_count' => 10]],
            ['key' => 'promotional_banners', 'title' => 'Offers for you', 'subtitle' => 'Limited-time farm offers', 'sort_order' => 140, 'config' => null],
            ['key' => 'app_download', 'title' => 'Download the AB Organic App', 'subtitle' => 'Unlock exclusive app-only discounts', 'sort_order' => 150, 'config' => [
                'android_url' => 'https://play.google.com',
                'ios_url' => 'https://apps.apple.com',
                'product_count' => null,
            ]],
            ['key' => 'testimonials', 'title' => 'Loved by our customers', 'subtitle' => 'Real stories from real families', 'sort_order' => 160, 'config' => ['product_count' => 8]],
            ['key' => 'cta', 'title' => 'Go Organic. Go Fresh. Go Fast.', 'subtitle' => 'Join thousands of families who trust AB Organic Farm for their daily groceries.', 'sort_order' => 170, 'config' => ['product_count' => null]],
        ];

        foreach ($sections as $i => $section) {
            HomepageSection::updateOrCreate(
                ['key' => $section['key']],
                [
                    'title' => $section['title'],
                    'subtitle' => $section['subtitle'],
                    'is_visible' => true,
                    'sort_order' => $section['sort_order'],
                    'config' => $section['config'],
                ]
            );
        }
    }
}