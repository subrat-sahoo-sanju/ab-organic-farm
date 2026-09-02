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

            // 2 · Welcome To Anveshan — icon tab menu (All/Ghee/Oils/Atta/Combos/Deal/Superfoods) + lazy product grid
            ['key' => 'welcome', 'title' => 'Welcome To AB Organic Farm!', 'subtitle' => "You're One Step Closer to Purity", 'sort_order' => 15, 'config' => [
                'product_count' => 12,
                'tabs' => [
                    ['title' => 'All', 'key' => 'all', 'type' => 'all', 'active_icon' => 'images/nav/nav-all-active.svg', 'inactive_icon' => 'images/nav/nav-all.svg'],
                    ['title' => 'Ghee', 'key' => 'ghee', 'type' => 'category', 'value' => 'ghee', 'active_icon' => 'images/nav/nav-ghee-active.svg', 'inactive_icon' => 'images/nav/nav-ghee.svg'],
                    ['title' => 'Oil', 'key' => 'oil', 'type' => 'category', 'value' => 'oil', 'active_icon' => 'images/nav/nav-oils-active.svg', 'inactive_icon' => 'images/nav/nav-oils.svg'],
                    ['title' => 'Atta', 'key' => 'atta', 'type' => 'category', 'value' => 'atta', 'active_icon' => 'images/nav/nav-atta-active.svg', 'inactive_icon' => 'images/nav/nav-atta.svg'],
                    ['title' => 'Combos', 'key' => 'combos', 'type' => 'keyword', 'value' => 'combo', 'fallback' => ['type' => 'categories', 'values' => ['ghee', 'oil', 'atta']], 'active_icon' => 'images/nav/nav-combos-active.svg', 'inactive_icon' => 'images/nav/nav-combos.svg'],
                    ['title' => 'Deal', 'key' => 'deal', 'type' => 'deal', 'active_icon' => 'images/nav/nav-deal-active.svg', 'inactive_icon' => 'images/nav/nav-deal.svg'],
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

            // 3.5 · You were checking these out earlier — hidden until a visitor has product history
            ['key' => 'recently_viewed', 'title' => 'You were checking these out earlier.', 'subtitle' => "Don't miss out; Complete your purchase Now.", 'sort_order' => 32, 'config' => ['product_count' => 12]],

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
            // Product in Focus — reference oils menu (Groundnut/Mustard/Sunflower/Olive/Coconut/Sesame icon tabs + horizontal rail + See All)
            ['key' => 'focus_oils', 'title' => 'Product in Focus:', 'subtitle' => 'Explore Our Cold-Pressed Oils', 'sort_order' => 35, 'config' => [
                'product_count' => 8,
                'tabs' => [
                    ['title' => 'Groundnut', 'key' => 'groundnut', 'type' => 'keyword', 'value' => 'groundnut', 'fallback' => ['type' => 'category', 'value' => 'oil'], 'active_icon' => 'images/nav/nav-groundnut-active.svg', 'inactive_icon' => 'images/nav/nav-groundnut.svg'],
                    ['title' => 'Mustard', 'key' => 'mustard', 'type' => 'keyword', 'value' => 'mustard oil', 'fallback' => ['type' => 'category', 'value' => 'oil'], 'active_icon' => 'images/nav/nav-mustard-active.svg', 'inactive_icon' => 'images/nav/nav-mustard.svg'],
                    ['title' => 'Sunflower', 'key' => 'sunflower', 'type' => 'keyword', 'value' => 'sunflower', 'fallback' => ['type' => 'category', 'value' => 'oil'], 'active_icon' => 'images/nav/nav-sunflower-active.svg', 'inactive_icon' => 'images/nav/nav-sunflower.svg'],
                    ['title' => 'Olive', 'key' => 'olive', 'type' => 'keyword', 'value' => 'olive', 'fallback' => ['type' => 'category', 'value' => 'oil'], 'active_icon' => 'images/nav/nav-olive-active.svg', 'inactive_icon' => 'images/nav/nav-olive.svg'],
                    ['title' => 'Coconut', 'key' => 'coconut', 'type' => 'keyword', 'value' => 'coconut oil', 'fallback' => ['type' => 'category', 'value' => 'oil'], 'active_icon' => 'images/nav/nav-coconut-active.svg', 'inactive_icon' => 'images/nav/nav-coconut.svg'],
                    ['title' => 'Sesame', 'key' => 'sesame', 'type' => 'keyword', 'value' => 'sesame', 'fallback' => ['type' => 'category', 'value' => 'oil'], 'active_icon' => 'images/nav/nav-sesame-active.svg', 'inactive_icon' => 'images/nav/nav-sesame.svg'],
                ],
            ]],

            // Product in Focus — reference ghee menu (Gir / Desi Cow / Buffalo / combo) after the oils menu
            ['key' => 'focus_ghee', 'title' => 'Product in Focus:', 'subtitle' => 'Explore Our A2 Desi Ghee', 'sort_order' => 36, 'config' => [
                'product_count' => 8,
                'tabs' => [
                    ['title' => 'Gir', 'key' => 'gir', 'type' => 'keyword', 'value' => 'gir', 'fallback' => ['type' => 'keyword', 'value' => 'ghee'], 'active_icon' => 'images/nav/nav-gir-active.svg', 'inactive_icon' => 'images/nav/nav-gir.svg'],
                    ['title' => 'Desi Cow', 'key' => 'desi-cow', 'type' => 'keyword', 'value' => 'ghee', 'fallback' => ['type' => 'category', 'value' => 'ghee'], 'active_icon' => 'images/nav/nav-desi-active.svg', 'inactive_icon' => 'images/nav/nav-desi.svg'],
                    ['title' => 'Buffalo', 'key' => 'buffalo', 'type' => 'keyword', 'value' => 'buffalo', 'fallback' => ['type' => 'keyword', 'value' => 'ghee'], 'active_icon' => 'images/nav/nav-buffalo-active.svg', 'inactive_icon' => 'images/nav/nav-buffalo.svg'],
                    ['title' => 'Combo', 'key' => 'ghee-combo', 'type' => 'keyword', 'value' => 'combo', 'fallback' => ['type' => 'keyword', 'value' => 'ghee'], 'active_icon' => 'images/nav/nav-combo-active.svg', 'inactive_icon' => 'images/nav/nav-combo.svg'],
                ],
            ]],
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