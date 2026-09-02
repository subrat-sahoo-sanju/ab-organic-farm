<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            // 1 · Hero slideshow — full-bleed, 2 slides (exact Anveshan images)
            ['key' => 'hero', 'title' => 'Hero', 'subtitle' => 'High Protein Atta · All Products', 'sort_order' => 10, 'config' => [
                'slides' => [
                    ['desktop' => 'sections/hero-desktop.webp', 'mobile' => 'sections/hero-mobile.webp', 'alt' => 'High Protein Atta', 'url' => route('shop.categories')],
                    ['desktop' => 'sections/hero2-desktop.webp', 'mobile' => 'sections/hero2-mobile.webp', 'alt' => 'Explore all products', 'url' => route('shop.categories')],
                ],
            ]],

            // 2 · Why Choose Anveshan (90×90 icon grid)
            ['key' => 'trust_badges', 'title' => 'Why Choose Anveshan?', 'subtitle' => 'The Anveshan difference', 'sort_order' => 20, 'config' => [
                'product_count' => null,
                'items' => [
                    ['image' => 'images/why-native.svg', 'icon' => 'map-pin', 'title' => 'Native Sourcing', 'text' => 'Highest quality raw material from native regions all over India.'],
                    ['image' => 'images/why-traditional.svg', 'icon' => 'leaf', 'title' => 'Traditional Processing', 'text' => 'Minimally processed using time-tested methods, made better. For maximum nutrition.'],
                    ['image' => 'images/why-quality.svg', 'icon' => 'shield-check', 'title' => 'Extensive Quality Checks', 'text' => 'Everything goes through rigorous lab tests, so you get only what is best.'],
                    ['image' => 'images/why-rural.svg', 'icon' => 'users', 'title' => 'Better Rural Lives', 'text' => 'Farmer families are empowered with every product you buy.'],
                ],
            ]],

            // 3 · Native Ingredients — gold-title carousel over full-width background
            ['key' => 'native_ingredients', 'title' => 'Native Ingredients. No Substitutes.', 'subtitle' => '', 'sort_order' => 30, 'config' => [
                'bg_desktop' => 'sections/native-bg.svg',
                'bg_mobile' => 'sections/native-bg-mobile.svg',
                'title_color' => '#B5762A',
                'carousel' => [
                    ['image' => 'sections/native1.jpg', 'url' => '', 'alt' => 'Native ingredients'],
                    ['image' => 'sections/native2.jpg', 'url' => '', 'alt' => 'Native ingredients'],
                    ['image' => 'sections/native3.jpg', 'url' => '', 'alt' => 'Native ingredients'],
                    ['image' => 'sections/native4.webp', 'url' => '', 'alt' => 'Native ingredients'],
                ],
            ]],

            // 4 · Only Perfect Makes The Cut — blue-title carousel over full-width background
            ['key' => 'quality', 'title' => 'Only Perfect Makes The Cut', 'subtitle' => '', 'sort_order' => 40, 'config' => [
                'bg_desktop' => 'sections/perfect-bg.svg',
                'bg_mobile' => 'sections/perfect-bg-mobile.svg',
                'title_color' => '#4199A8',
                'carousel' => [
                    ['image' => 'sections/perfect1.webp', 'url' => '', 'alt' => 'Only perfect makes the cut'],
                    ['image' => 'sections/perfect2.webp', 'url' => '', 'alt' => 'Only perfect makes the cut'],
                    ['image' => 'sections/perfect3.webp', 'url' => '', 'alt' => 'Only perfect makes the cut'],
                    ['image' => 'sections/perfect4.webp', 'url' => '', 'alt' => 'Only perfect makes the cut'],
                ],
            ]],

            // 5 · Healthy Combo Packs — horizontal product rail
            ['key' => 'combos', 'title' => 'Healthy Combo Packs', 'subtitle' => '', 'sort_order' => 50, 'config' => ['product_count' => 10]],

            // 6 · Explore our Superfoods — horizontal product rail
            ['key' => 'superfoods', 'title' => 'Explore our Superfoods', 'subtitle' => '', 'sort_order' => 60, 'config' => ['product_count' => 10]],

            // 7 · Customer Reviews
            ['key' => 'testimonials', 'title' => 'What Do Our Customers Say', 'subtitle' => '', 'sort_order' => 70, 'config' => ['product_count' => 8]],

            // Not on the reference homepage — kept for admin, hidden by default.
            ['key' => 'welcome', 'title' => 'Welcome to AB Organic Farm!', 'subtitle' => "You're one step closer to purity. Tap a category to explore.", 'sort_order' => 80, 'config' => ['product_count' => 12, 'tabs' => null], 'is_visible' => false],
            ['key' => 'focus_oils', 'title' => 'Product in Focus: Explore Our Cold-Pressed Oils', 'subtitle' => 'Groundnut · Mustard · Sunflower · Olive · Coconut · Sesame', 'sort_order' => 90, 'config' => ['product_count' => 8, 'tabs' => null], 'is_visible' => false],
            ['key' => 'focus_ghee', 'title' => 'Product in Focus: Explore Our A2 Desi Ghee', 'subtitle' => 'Bilona-churned, made with patience', 'sort_order' => 100, 'config' => ['product_count' => 8, 'tabs' => null], 'is_visible' => false],
            ['key' => 'best_sellers', 'title' => 'Best Sellers', 'subtitle' => 'Trusted by thousands of households', 'sort_order' => 110, 'config' => ['product_count' => 10], 'is_visible' => false],
            ['key' => 'trending', 'title' => 'Trending Now', 'subtitle' => 'What our community is loving right now', 'sort_order' => 120, 'config' => ['product_count' => 10], 'is_visible' => false],
            ['key' => 'new_arrivals', 'title' => 'New Arrivals', 'subtitle' => 'Just stocked, fresh off the farm', 'sort_order' => 130, 'config' => ['product_count' => 10], 'is_visible' => false],
            ['key' => 'logo_slider', 'title' => 'Trusted by', 'subtitle' => '', 'sort_order' => 140, 'config' => ['product_count' => null], 'is_visible' => false],
            ['key' => 'app_download', 'title' => 'Download the AB Organic App', 'subtitle' => 'Order, track and save — all from the AB Organic app.', 'sort_order' => 150, 'config' => [
                'images' => ['desktop' => 'sections/app-icon.jpg', 'mobile' => 'sections/app-icon.jpg', 'alt' => 'AB Organic app'],
                'android_url' => '#',
                'ios_url' => '#',
            ], 'is_visible' => false],

            // Promotional banner strips managed from Admin → Banners (placement "Promotional")
            ['key' => 'promotional_banners', 'title' => 'Promotions & Deals', 'subtitle' => '', 'sort_order' => 45, 'config' => ['product_count' => null], 'is_visible' => false],
        ];

        $seen = collect($sections)->pluck('key');

        // Remove obsolete keys no longer used by the live layout.
        HomepageSection::whereNotIn('key', $seen)->whereIn('key', ['deals', 'organic_picks', 'recommended', 'categories', 'cta'])->delete();

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['key' => $section['key']],
                [
                    'title' => $section['title'],
                    'subtitle' => $section['subtitle'],
                    'is_visible' => $section['is_visible'] ?? true,
                    'sort_order' => $section['sort_order'],
                    'config' => $section['config'],
                ]
            );
        }
    }
}