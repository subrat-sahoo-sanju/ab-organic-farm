<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            // 1 · Announcement bar (static via settings, not a section)

            // 1 · Hero slideshow
            ['key' => 'hero', 'title' => 'Farm Fresh, Delivered Daily', 'subtitle' => '100% organic produce, straight from the farm', 'sort_order' => 10, 'config' => [
                'images' => ['desktop' => 'sections/hero-desktop.jpg', 'mobile' => 'sections/hero-mobile.jpg', 'alt' => 'AB Organic Farm — fresh groceries'],
            ]],

            // 2 · Welcome tab menu + dynamic grid
            ['key' => 'welcome', 'title' => 'Welcome to AB Organic Farm!', 'subtitle' => "You're one step closer to purity. Tap a category to explore.", 'sort_order' => 20, 'config' => ['product_count' => 12, 'tabs' => null]],

            // 3 · Why Choose Anveshan (icons)
            ['key' => 'trust_badges', 'title' => 'Why Choose Anveshan?', 'subtitle' => 'The Anveshan difference, in four pillars', 'sort_order' => 30, 'config' => [
                'product_count' => null,
                'items' => [
                    ['icon' => 'map-pin', 'title' => 'Native Sourcing', 'text' => 'Highest quality raw material from native regions all over India.'],
                    ['icon' => 'leaf', 'title' => 'Traditional Processing', 'text' => 'Minimally processed using time-tested methods, made better. For maximum nutrition.'],
                    ['icon' => 'shield-check', 'title' => 'Extensive Quality Checks', 'text' => 'Everything goes through rigorous lab tests, so you get only what is best.'],
                    ['icon' => 'users', 'title' => 'Better Rural Lives', 'text' => 'Farmer families are empowered with every product you buy.'],
                ],
            ]],

            // 4 · Native Ingredients image banner
            ['key' => 'native_ingredients', 'title' => 'Native Ingredients. No Substitutes.', 'subtitle' => '', 'sort_order' => 40, 'config' => [
                'images' => ['desktop' => 'sections/hero-desktop.jpg', 'mobile' => 'sections/hero-mobile.jpg', 'alt' => 'Native ingredients'],
            ]],

            // 5 · Product in Focus: oils
            ['key' => 'focus_oils', 'title' => 'Product in Focus: Explore Our Cold-Pressed Oils', 'subtitle' => 'Groundnut · Mustard · Sunflower · Olive · Coconut · Sesame', 'sort_order' => 50, 'config' => ['product_count' => 8, 'tabs' => null]],

            // 6 · Product in Focus: ghee
            ['key' => 'focus_ghee', 'title' => 'Product in Focus: Explore Our A2 Desi Ghee', 'subtitle' => 'Bilona-churned, made with patience', 'sort_order' => 60, 'config' => ['product_count' => 8, 'tabs' => null]],

            // 7 · Only Perfect Makes The Cut (image banner)
            ['key' => 'quality', 'title' => 'Only Perfect Makes The Cut', 'subtitle' => 'Every jar is crafted, tested and hand-picked', 'sort_order' => 70, 'config' => [
                'images' => ['desktop' => 'sections/hero-desktop.jpg', 'mobile' => 'sections/hero-mobile.jpg', 'alt' => 'Only perfect makes the cut'],
            ]],

            // 8 · Combo products (horizontal)
            ['key' => 'combos', 'title' => 'Family Deals & Combos', 'subtitle' => 'Save more with our curated combos', 'sort_order' => 80, 'config' => ['product_count' => 10]],

            // 9 · Superfoods (horizontal)
            ['key' => 'superfoods', 'title' => 'Explore Our Superfoods', 'subtitle' => '', 'sort_order' => 90, 'config' => ['product_count' => 10]],

            // 10 · Best Sellers (rail)
            ['key' => 'best_sellers', 'title' => 'Best Sellers', 'subtitle' => 'Trusted by thousands of households', 'sort_order' => 100, 'config' => ['product_count' => 10]],

            // 11 · Trending (rail)
            ['key' => 'trending', 'title' => 'Trending Now', 'subtitle' => 'What our community is loving right now', 'sort_order' => 110, 'config' => ['product_count' => 10]],

            // 12 · New Arrivals (rail)
            ['key' => 'new_arrivals', 'title' => 'New Arrivals', 'subtitle' => 'Just stocked, fresh off the farm', 'sort_order' => 120, 'config' => ['product_count' => 10]],

            // 13 · Customer Reviews
            ['key' => 'testimonials', 'title' => 'What Do Our Customers Say', 'subtitle' => 'Real stories from real families', 'sort_order' => 130, 'config' => ['product_count' => 8]],

            // 14 · Logo marquee
            ['key' => 'logo_slider', 'title' => 'Trusted by', 'subtitle' => '', 'sort_order' => 140, 'config' => ['product_count' => null]],

            // 15 · Download the App
            ['key' => 'app_download', 'title' => 'AB Organic is Now on your Palm', 'subtitle' => 'Order, track and save — all from the AB Organic app.', 'sort_order' => 150, 'config' => [
                'images' => ['desktop' => 'sections/app-icon.jpg', 'mobile' => 'sections/app-icon.jpg', 'alt' => 'AB Organic app'],
                'android_url' => '#',
                'ios_url' => '#',
            ]],
        ];

        $seen = collect($sections)->pluck('key');

        // Remove obsolete keys no longer used by the live reference layout.
        HomepageSection::whereNotIn('key', $seen)->whereIn('key', ['deals', 'organic_picks', 'recommended', 'promotional_banners', 'categories', 'cta'])->delete();

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