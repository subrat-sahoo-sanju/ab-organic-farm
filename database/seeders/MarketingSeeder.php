<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Discount;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class MarketingSeeder extends Seeder
{
    public function run(): void
    {
        // ---- Discounts ----
        $riceCat = Category::where('slug', 'rice')->first();

        Discount::firstOrCreate(
            ['name' => 'Monsoon Rice Fest', 'scope' => 'category'],
            [
                'type' => 'percentage',
                'value' => 10,
                'scope' => 'category',
                'discountable_type' => Category::class,
                'discountable_id' => $riceCat->id,
                'max_discount_amount' => 150,
                'starts_at' => now()->subDays(2),
                'ends_at' => now()->addDays(12),
            ]
        );

        $almonds = Product::where('name', 'Organic Almonds (California)')->first();
        Discount::firstOrCreate(
            ['name' => 'Almond Flash Deal', 'scope' => 'product'],
            [
                'type' => 'fixed',
                'value' => 100,
                'scope' => 'product',
                'discountable_type' => Product::class,
                'discountable_id' => $almonds?->id,
                'starts_at' => now()->subDay(),
                'ends_at' => now()->addDays(5),
            ]
        );

        $brownRice = Product::where('name', 'Organic Brown Rice')->first();
        Discount::firstOrCreate(
            ['name' => 'Brown Rice Bulk Saver', 'scope' => 'product'],
            [
                'type' => 'bulk_tier',
                'value' => 0,
                'tiers' => [['qty' => 2, 'percent' => 5], ['qty' => 5, 'percent' => 10]],
                'scope' => 'product',
                'discountable_type' => Product::class,
                'discountable_id' => $brownRice?->id,
            ]
        );

        // ---- Coupons ----
        Coupon::firstOrCreate(['code' => 'FRESH15'], [
            'name' => '15% off for new customers',
            'type' => 'percentage',
            'value' => 15,
            'max_discount_amount' => 200,
            'min_order_amount' => 499,
            'per_user_limit' => 1,
            'first_order_only' => true,
        ]);

        Coupon::firstOrCreate(['code' => 'ORGANIC100'], [
            'name' => 'Flat ₹100 off above ₹999',
            'type' => 'fixed',
            'value' => 100,
            'min_order_amount' => 999,
            'usage_limit' => 500,
            'per_user_limit' => 3,
        ]);

        Coupon::firstOrCreate(['code' => 'GHEE50'], [
            'name' => '₹50 off on ghee & oils',
            'type' => 'fixed',
            'value' => 50,
            'min_order_amount' => 400,
            'per_user_limit' => 2,
        ]);
        if ($gheeCoupon = Coupon::where('code', 'GHEE50')->first()) {
            $oils = Category::where('slug', 'oils-ghee')->first();
            $gheeCoupon->categories()->sync([$oils->id]);
        }

        // ---- Banners ----
        Banner::firstOrCreate(['title' => 'Good Food. Naturally Better.'], [
            'placement' => 'hero',
            'subtitle' => 'Certified organic essentials delivered from partner farms to your doorstep.',
            'button_text' => 'Shop Organic Products',
            'button_url' => '/categories/fruits-vegetables',
            'sort_order' => 0,
        ]);

        Banner::firstOrCreate(['title' => 'Cold-Pressed Oils Week'], [
            'placement' => 'promotional',
            'subtitle' => 'Up to 12% off wood-pressed oils & A2 ghee.',
            'button_text' => 'Grab the deal',
            'button_url' => '/categories/oils-ghee',
            'sort_order' => 1,
        ]);

        // ---- Homepage sections ----
        $sections = [
            ['hero', 'Hero banner', null, true, 0],
            ['trust_badges', 'Why shop with us', null, true, 1],
            ['categories', 'Shop by Category', 'Everything organic, neatly sorted', true, 2],
            ['deals', "Today's Deals", 'Hand-picked savings ending soon', true, 3],
            ['trending', 'Trending Now', 'What Bhubaneswar is buying this week', true, 4],
            ['best_sellers', 'Best Sellers', 'Our all-time farm favourites', true, 5],
            ['promotional_banners', 'Offers for you', null, true, 6],
            ['new_arrivals', 'New Arrivals', 'Fresh from the farms', true, 7],
            ['organic_picks', 'Organic Picks', 'Curated by our sourcing team', true, 8],
            ['recommended', 'Recommended For You', null, true, 9],
            ['recently_viewed', 'Recently Viewed', null, true, 10],
            ['testimonials', 'Loved by our customers', null, true, 11],
        ];

        foreach ($sections as [$key, $title, $subtitle, $visible, $sort]) {
            HomepageSection::updateOrCreate(
                ['key' => $key],
                ['title' => $title, 'subtitle' => $subtitle, 'is_visible' => $visible, 'sort_order' => $sort]
            );
        }

        // ---- Testimonials ----
        $testimonials = [
            ['Ankita Mohanty', 'Bhubaneswar', 'The brown rice and cold-pressed oil are staples in my kitchen now. Everything arrives fresh and beautifully packed.', 5],
            ['Rahul Panda', 'Cuttack', 'Finally an organic store that actually delivers what it promises. The COD option makes it effortless to trust the process.', 5],
            ['Sneha Rath', 'Saheed Nagar', 'Their seasonal fruit boxes are wonderful — you can genuinely taste the difference from regular store produce.', 4],
            ['Debashish Nayak', 'Patia', 'Ordered the A2 ghee thrice already. Consistent quality and the delivery partner is always courteous.', 5],
        ];

        foreach ($testimonials as $i => [$name, $loc, $msg, $rating]) {
            Testimonial::updateOrCreate(
                ['customer_name' => $name],
                ['location' => $loc, 'message' => $msg, 'rating' => $rating, 'sort_order' => $i]
            );
        }
    }
}
