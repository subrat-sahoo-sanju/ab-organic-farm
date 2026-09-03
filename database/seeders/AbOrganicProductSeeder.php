<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\InventoryTransaction;
use App\Services\InventoryService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

/**
 * Seeds the AB Organic Farm home-shop catalog (Ghee / Oil / Atta) against the
 * real category tree, mirroring exactly what the admin "Add Product" creates
 * (product -> default variant -> inventory via InventoryService).
 *
 * Idempotent: safe to re-run. Products get a clean branded SVG placeholder;
 * replace them later with AI-generated photos via Admin -> Products.
 */
class AbOrganicProductSeeder extends Seeder
{
    public function run(): void
    {
        $inventory = app(InventoryService::class);
        $brand     = Brand::where('name', 'LIKE', '%AB Organic Farm%')->first() ?? Brand::first();

        $cat = fn (string $slug) => Category::where('slug', $slug)->first();
        $ghee  = $cat('ghee');
        $gheeJ = $cat('ghee-jar-type') ?? $ghee;
        $gheeP = $cat('ghee-packed-type') ?? $ghee;
        $gheeM = $cat('ghee-multitype') ?? $ghee;
        $oil   = $cat('oil');
        $atta  = $cat('atta');

        // [categoryId, name, sku, regular, sale|null, unitLabel, flags]
        $rows = [
            [$gheeJ->id, 'Organic A2 Gir Cow Desi Ghee (Jar)', 'AB-GHEE-A2-500', 1290, 1099, '500 ml', ['best', 'featured']],
            [$gheeJ->id, 'Organic A2 Gir Cow Desi Ghee (Jar)', 'AB-GHEE-A2-1000', 2490, 2190, '1 litre', ['best', 'featured']],
            [$gheeJ->id, 'Pure Village Cow Ghee (Jar)', 'AB-GHEE-VIL-500', 990, 899, '500 ml', ['featured']],
            [$gheeP->id, 'Desi Ghee - Packed', 'AB-GHEE-PCK-500', 940, 849, '500 g', []],
            [$gheeM->id, 'Multitype Ghee Gift Pack', 'AB-GHEE-MUL-1', 2800, 2490, 'Gift pack', ['new']],
            [$oil->id, 'Kachhi Ghani Mustard Oil', 'AB-OIL-MUS-1', 540, 479, '1 litre', ['best']],
            [$oil->id, 'Cold-Pressed Groundnut (Peanut) Oil', 'AB-OIL-GND-1', 610, 549, '1 litre', ['featured', 'new']],
            [$oil->id, 'Virgin Coconut Oil', 'AB-OIL-COC-500', 680, 599, '500 ml', ['featured']],
            [$atta->id, 'Stone-Ground Whole Wheat Atta', 'AB-ATTA-WW-5', 340, 289, '5 kg', ['best', 'featured']],
            [$atta->id, 'Multigrain Atta', 'AB-ATTA-MG-5', 410, 359, '5 kg', ['new']],
        ];

        foreach ($rows as [$catId, $name, $sku, $regular, $sale, $unit, $flags]) {
            if (Product::where('sku', $sku)->exists()) {
                continue;
            }

            $product = Product::create([
                'uuid' => (string) \Illuminate\Support\Str::uuid(),
                'category_id' => $catId,
                'brand_id' => $brand?->id,
                'name' => $name,
                'slug' => $this->uniqueSlug($name),
                'sku' => $sku,
                'short_description' => "Farm-fresh {$name} — cold-processed in small batches, no additives.",
                'description' => "{$name} from AB Organic Farm’s partner grower collective.\n\nMade the traditional way using natural, chemical-free farming and stone/cold-press methods. Tested for purity and traceable to its source farm.",
                'ingredients' => '100% pure single ingredient. No preservatives, no artificial colouring.',
                'benefits' => "• Certified organic\n• Small-batch traditional processing\n• No synthetic pesticides\n• Farm-traceable",
                'origin' => 'Odisha, India',
                'farmer_source' => 'GreenRoot Farmer Collective',
                'certification' => 'India Organic · NPOP',
                'is_organic' => true,
                'cost_price' => round($regular * 0.6, 2),
                'regular_price' => $regular,
                'sale_price' => $sale,
                'unit_label' => $unit,
                'status' => 'active',
                'is_featured' => in_array('featured', $flags),
                'is_best_seller' => in_array('best', $flags),
                'is_new_arrival' => in_array('new', $flags),
                'sold_count' => random_int(50, 800),
                'view_count' => random_int(400, 5000),
                'published_at' => now()->subDays(random_int(1, 45)),
            ]);

            $variant = ProductVariant::updateOrCreate(
                ['sku' => $sku.'-DEFAULT'],
                [
                    'product_id' => $product->id,
                    'name' => 'Default',
                    'unit_label' => $unit,
                    'price' => $regular,
                    'sale_price' => $sale,
                    'cost_price' => round($regular * 0.6, 2),
                    'weight_grams' => preg_match('/l|litre/i', $unit) ? 1000 : 500,
                    'is_default' => true,
                    'is_active' => true,
                    'sort_order' => 0,
                ]
            );

            $inv = $inventory->ensureForVariant($variant);
            $stock = random_int(20, 120);
            $inv->update(['stock' => $stock, 'low_stock_threshold' => 10]);
            InventoryTransaction::create([
                'inventory_id' => $inv->id,
                'user_id' => null,
                'type' => 'purchase',
                'quantity' => $stock,
                'stock_after' => $stock,
                'reason' => 'Opening stock (seed)',
            ]);

            ProductImage::create([
                'product_id' => $product->id,
                'path' => $this->makeProductSvg($product),
                'thumb_path' => $this->makeProductSvg($product),
                'alt_text' => $name,
                'sort_order' => 0,
                'is_primary' => true,
            ]);
        }

        $this->seedReviews();
    }

    /** Approved reviews feed both the "Trending" rail and the testimonials section. */
    protected function seedReviews(): void
    {
        $names = ['Priya', 'Rakesh', 'Sundar', 'Meera', 'Anita', 'Debasish', 'Lakshmi', 'Rajesh'];
        $bodies = [
            'Genuinely farm-fresh — you can taste the difference. Packaging was excellent.',
            'Ordered for my family, everyone loved it. Will reorder for sure.',
            'Pure and authentic. Exactly what you expect from an organic brand.',
            'Great price for the quality. Delivered fast and fresh.',
            'Best desi ghee I have tried. Aroma is incredible after it melts.',
            'Very satisfied. The cold-press method really preserves the nutrients.',
            'Quality is top notch and it lasts long. Highly recommended.',
            'My mother says it tastes just like the village ghee. Perfect.',
        ];
        $customerIds = \App\Models\User::where('id', '>', 6)->orderBy('id')->pluck('id')->all();
        if (empty($customerIds)) {
            $customerIds = [1];
        }
        foreach (Product::limit(10)->get() as $i => $product) {
            foreach ([0, 3] as $n) {
                $j = ($i * 2 + $n) % count($names);
                \App\Models\Review::firstOrCreate(
                    [
                        'product_id' => $product->id,
                        'user_id' => $customerIds[$j % count($customerIds)],
                        'rating' => [5, 5, 4][$n % 3],
                        'body' => $bodies[$i % count($bodies)],
                    ],
                    [
                        'title' => 'Verified purchase',
                        'order_id' => null,
                        'image_path' => null,
                        'status' => 'approved',
                        'is_featured' => $n === 0,
                    ]
                );
            }
        }
    }

    protected function uniqueSlug(string $name): string
    {
        $base = \Illuminate\Support\Str::slug($name) ?: \Illuminate\Support\Str::random(8);
        $slug = $base;
        $i = 1;
        while (Product::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.(++$i);
        }
        return $slug;
    }

    /** Branded SVG placeholder so seeded products render nicely until photos are uploaded. */
    protected function makeProductSvg(Product $product): string
    {
        $palettes = [
            ['#F4F0E1', '#14532D', '#B5762A'],
            ['#EAF4EC', '#0B3B30', '#22A762'],
            ['#FFF6E5', '#7A4B16', '#E3A11B'],
        ];
        [$bg, $fg, $accent] = $palettes[$product->id % count($palettes)];
        $label = strtoupper($product->name);
        $short = collect(explode(' ', $product->name))->take(4)->implode(' ');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="1000" height="1000" viewBox="0 0 1000 1000">
  <rect width="1000" height="1000" fill="{$bg}"/>
  <circle cx="500" cy="420" r="300" fill="{$accent}" opacity="0.18"/>
  <circle cx="500" cy="420" r="205" fill="{$accent}" opacity="0.32"/>
  <text x="500" y="300" font-family="Georgia, serif" font-size="150" font-weight="bold" fill="#ffffff" text-anchor="middle">AB</text>
  <text x="500" y="360" font-family="Georgia, serif" font-size="42" letter-spacing="6" fill="{$fg}" text-anchor="middle" opacity="0.85">ORGANIC FARM</text>
  <text x="500" y="520" font-family="Verdana, sans-serif" font-size="52" font-weight="700" fill="{$fg}" text-anchor="middle">{$short}</text>
  <text x="500" y="620" font-family="Verdana, sans-serif" font-size="30" fill="{$fg}" opacity="0.6" text-anchor="middle">{$product->unit_label}</text>
</svg>
SVG;

        $file = "products/{$product->slug}.svg";
        Storage::disk('public')->put($file, $svg);

        // Stored without the leading "storage/" so it resolves correctly via
        // asset('storage/'.$path) in the storefront. See ProductImageController.
        return $file;
    }
}