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
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $inventory = app(InventoryService::class);

        // ---------------- Categories (nested) ----------------
        $tree = [
            'Fruits & Vegetables' => [
                'slug' => 'fruits-vegetables', 'icon' => 'apple', 'featured' => true,
                'children' => [
                    'Fresh Fruits' => ['slug' => 'fresh-fruits', 'icon' => 'citrus'],
                    'Fresh Vegetables' => ['slug' => 'fresh-vegetables', 'icon' => 'carrot'],
                ],
            ],
            'Rice, Grains & Flour' => [
                'slug' => 'rice-grains-flour', 'icon' => 'wheat', 'featured' => true,
                'children' => [
                    'Rice' => ['slug' => 'rice', 'icon' => 'wheat'],
                    'Millets & Grains' => ['slug' => 'millets-grains', 'icon' => 'sprout'],
                    'Atta & Flours' => ['slug' => 'atta-flours', 'icon' => 'cookie'],
                ],
            ],
            'Pulses & Dal' => [
                'slug' => 'pulses-dal', 'icon' => 'bean', 'featured' => true,
                'children' => [],
            ],
            'Spices & Masala' => [
                'slug' => 'spices-masala', 'icon' => 'flame-kindling', 'featured' => true,
                'children' => [],
            ],
            'Oils & Ghee' => [
                'slug' => 'oils-ghee', 'icon' => 'droplets', 'featured' => true,
                'children' => [],
            ],
            'Dry Fruits & Nuts' => [
                'slug' => 'dry-fruits-nuts', 'icon' => 'nut', 'featured' => true,
                'children' => [],
            ],
            'Healthy Snacks' => [
                'slug' => 'healthy-snacks', 'icon' => 'candy', 'featured' => false,
                'children' => [],
            ],
            'Personal Care' => [
                'slug' => 'personal-care', 'icon' => 'sparkles', 'featured' => false,
                'children' => [],
            ],
            'Herbal Wellness' => [
                'slug' => 'herbal-wellness', 'icon' => 'leaf', 'featured' => false,
                'children' => [],
            ],
            'Household' => [
                'slug' => 'household', 'icon' => 'home', 'featured' => false,
                'children' => [],
            ],
        ];

        $order = 0;
        foreach ($tree as $name => $meta) {
            $parent = Category::updateOrCreate(
                ['slug' => $meta['slug']],
                [
                    'name' => $name,
                    'icon' => $meta['icon'],
                    'is_featured' => $meta['featured'],
                    'sort_order' => $order++,
                    'description' => "Certified organic {$name} sourced directly from partner farms.",
                ]
            );

            foreach (($meta['children'] ?? []) as $childName => $childMeta) {
                Category::updateOrCreate(
                    ['slug' => $childMeta['slug']],
                    [
                        'name' => $childName,
                        'parent_id' => $parent->id,
                        'icon' => $childMeta['icon'],
                        'sort_order' => $order++,
                    ]
                );
            }
        }

        $cat = fn (string $slug) => Category::where('slug', $slug)->first();

        // ---------------- Brands / farmer collectives ----------------
        $brands = [
            'AB Organic Farm Own' => 'ab-organic-farm',
            'Sattva Organics' => 'sattva-organics',
            'GreenRoot Co-op' => 'greenroot-coop',
            'Nature Basket Farms' => 'nature-basket-farms',
        ];
        foreach ($brands as $bName => $bSlug) {
            Brand::updateOrCreate(['slug' => $bSlug], ['name' => $bName]);
        }

        // ---------------- Products ----------------
        // [categorySlug, name, price, salePrice|null, unit variants [[label,g,sale|null]...], flags]
        $products = [
            ['rice', 'Organic Brown Rice', 560, null, [['1 kg', 1000, null], ['5 kg', 420, 460]], ['organic', 'best']],
            ['rice', 'Organic Basmati Rice', 780, 649, [['1 kg', 1000, null], ['5 kg', 5000, 620]], ['organic', 'featured']],
            ['rice', 'Organic Red Rice', 320, null, [['1 kg', 1000, null]], ['organic']],
            ['millets-grains', 'Organic Foxtail Millet', 180, 149, [['1 kg', 1000, null]], ['organic', 'new']],
            ['millets-grains', 'Organic Quinoa', 690, null, [['500 g', 500, null]], ['organic']],
            ['atta-flours', 'Organic Whole Wheat Atta', 340, 289, [['5 kg', 5000, null]], ['organic', 'best']],
            ['atta-flours', 'Organic Jaggery Powder', 210, null, [['500 g', 500, null]], ['organic']],
            ['pulses-dal', 'Organic Toor Dal', 480, null, [['1 kg', 1000, null]], ['organic', 'best']],
            ['pulses-dal', 'Organic Moong Dal', 390, 349, [['1 kg', 1000, null]], ['organic']],
            ['pulses-dal', 'Organic Chana Dal', 260, null, [['1 kg', 1000, null]], ['organic']],
            ['pulses-dal', 'Organic Rajma Chitra', 520, null, [['500 g', 500, null]], ['organic', 'new']],
            ['spices-masala', 'Organic Turmeric Powder', 160, null, [['200 g', 200, null], ['500 g', 500, 360]], ['organic', 'best']],
            ['spices-masala', 'Organic Red Chilli Powder', 220, null, [['200 g', 200, null]], ['organic']],
            ['spices-masala', 'Organic Garam Masala', 280, 239, [['100 g', 100, null]], ['organic']],
            ['spices-masala', 'Organic Cumin Seeds', 310, null, [['250 g', 250, null]], ['organic']],
            ['oils-ghee', 'Wood Cold Pressed Groundnut Oil', 540, 479, [['1 L', 910, null]], ['organic', 'featured']],
            ['oils-ghee', 'Organic A2 Desi Cow Ghee', 1290, 1099, [['500 ml', 480, null], ['1 L', 940, 2090]], ['organic', 'best']],
            ['oils-ghee', 'Organic Coconut Oil', 680, null, [['500 ml', 470, null]], ['organic']],
            ['fruits-vegetables', 'Farm Fresh Organic Bananas', 89, null, [['500 g', 500, null]], ['organic', 'new']],
            ['fruits-vegetables', 'Organic Tomatoes', 68, null, [['500 g', 500, null]], ['organic']],
            ['fruits-vegetables', 'Organic Spinach', 45, null, [['250 g', 250, null]], ['organic']],
            ['fruits-vegetables', 'Himalayan Organic Apples', 380, 299, [['1 kg', 1000, null]], ['organic', 'featured']],
            ['dry-fruits-nuts', 'Organic Almonds (California)', 1250, 999, [['500 g', 500, null]], [], 'best'],
            ['dry-fruits-nuts', 'Organic Walnuts Kernel', 1450, null, [['250 g', 250, null]], ['organic']],
            ['dry-fruits-nuts', 'Organic Golden Raisins', 350, null, [['500 g', 500, null]], ['organic']],
            ['healthy-snacks', 'Organic Multigrain Roasted Chips', 120, 99, [['80 g', 80, null]], ['organic', 'new']],
            ['healthy-snacks', 'Organic Peanut Butter Crunchy', 450, null, [['500 g', 500, null]], []],
            ['personal-care', 'Organic Neem & Tulsi Soap', 95, null, [['100 g', 100, null]], ['organic']],
            ['personal-care', 'Natural Handmade Herbal Shampoo Bar', 240, 199, [['75 g', 75, null]], ['organic', 'new']],
            ['herbal-wellness', 'Organic Ashwagandha Root Powder', 399, null, [['200 g', 200, null]], ['organic']],
            ['herbal-wellness', 'Organic Triphala Churna', 290, null, [['200 g', 200, null]], ['organic']],
            ['household', 'Organic Dish Wash Liquid — Neem', 190, 159, [['500 ml', 520, null]], []],
        ];

        foreach ($products as $row) {
            [$catSlug, $name, $regular, $sale, $variants, $flags] = array_pad($row, 6, []);

            if (! is_array($flags)) {
                $flags = [];
            }
            if (isset($row[5]) && ! is_array($row[5])) {
                $flags = ['best'];
            }

            $product = Product::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'uuid' => (string) Str::uuid(),
                    'sku' => 'VFD-'.Str::upper(substr(md5(Str::slug($name)), 0, 10)),
                    'category_id' => $cat($catSlug)->id,
                    'brand_id' => Brand::where('slug', 'ab-organic-farm')->value('id') ?? Brand::first()?->id,
                    'name' => $name,
                    'short_description' => "{$name} — grown without synthetic pesticides or chemical fertilisers. Certified organic, farm-traceable.",
                    'description' => "Experience the pure taste of ".strtolower(preg_replace('/^Organic /', '', $name))." from AB Organic Farm's partner growers.\n\nCultivated using time-honoured natural farming methods, every batch is tested for purity and traceable to its source farm. No synthetic pesticides. No chemical fertilisers. No artificial ripening agents.",
                    'ingredients' => str_contains(strtolower($name), 'masala') || str_contains(strtolower($name), 'churna') ? 'Single-origin whole ingredients, stone-ground in small batches. Nothing else.' : '100% pure single ingredient. No preservatives, no additives.',
                    'benefits' => "• Certified organic cultivation\n• Traceable to partner farms\n• No synthetic pesticides or GMOs\n• Packed fresh in small batches",
                    'storage_instructions' => 'Store in a cool, dry place away from direct sunlight. Transfer to an airtight container after opening for best freshness.',
                    'origin' => 'Odisha, India',
                    'farmer_source' => 'GreenRoot Farmer Collective',
                    'certification' => 'India Organic · NPOP',
                    'is_organic' => in_array('organic', $flags),
                    'cost_price' => round($regular * 0.62, 2),
                    'regular_price' => $regular,
                    'sale_price' => $sale,
                    'status' => 'active',
                    'is_featured' => in_array('featured', $flags),
                    'is_best_seller' => in_array('best', $flags),
                    'is_new_arrival' => in_array('new', $flags),
                    'sold_count' => random_int(40, 900),
                    'view_count' => random_int(300, 6000),
                    'published_at' => now()->subDays(random_int(1, 60)),
                ]
            );

            foreach ($variants as $i => [$label, $grams, $vSale]) {
                $variant = ProductVariant::updateOrCreate(
                    ['sku' => $product->sku.'-'.preg_replace('/[^A-Za-z0-9]/', '', $label)],
                    [
                        'product_id' => $product->id,
                        'name' => $label,
                        'weight_grams' => $grams,
                        'unit_label' => $label,
                        'price' => $this->scalePrice($regular, $grams, $variants[0][1]),
                        'sale_price' => $i === 0 ? $sale : $vSale,
                        'cost_price' => round($this->scalePrice($regular, $grams, $variants[0][1]) * 0.62, 2),
                        'is_default' => $i === 0,
                        'sort_order' => $i,
                    ]
                );

                $inv = $inventory->ensureForVariant($variant);

                // Deterministic-ish stock: mostly healthy, a couple low/out-of-stock for demo
                $stock = match (true) {
                    $product->name === 'Organic Spinach' && $variant->is_default => 8,
                    $product->name === 'Organic Quinoa' => 0,
                    default => random_int(25, 140),
                };
                $inv->update(['stock' => $stock]);

                InventoryTransaction::create([
                    'inventory_id' => $inv->id,
                    'user_id' => null,
                    'type' => 'purchase',
                    'quantity' => $stock,
                    'stock_after' => $stock,
                    'reason' => 'Opening stock',
                ]);
            }

            // Generated SVG product image (offline-friendly placeholders)
            $imagePath = $this->makeProductSvg($product);
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $imagePath,
                'thumb_path' => $imagePath,
                'alt_text' => $product->name,
                'sort_order' => 0,
                'is_primary' => true,
            ]);
        }

        // Related products: within same category + a few bought-together pairs
        foreach (Product::all() as $p) {
            $similar = Product::where('category_id', $p->category_id)->whereKeyNot($p->id)->inRandomOrder()->limit(4)->get();
            foreach ($similar as $s) {
                \App\Models\RelatedProduct::firstOrCreate([
                    'product_id' => $p->id,
                    'related_product_id' => $s->id,
                    'type' => 'similar',
                ], ['score' => random_int(1, 50)]);
            }
        }

        $fbt = [
            ['Organic Brown Rice', 'Organic Toor Dal'],
            ['Organic Brown Rice', 'Organic A2 Desi Cow Ghee'],
            ['Organic Whole Wheat Atta', 'Wood Cold Pressed Groundnut Oil'],
            ['Organic Toor Dal', 'Organic Turmeric Powder'],
            ['Organic Almonds (California)', 'Organic Golden Raisins'],
        ];
        foreach ($fbt as [$a, $b]) {
            $pa = Product::where('name', $a)->first();
            $pb = Product::where('name', $b)->first();
            if ($pa && $pb) {
                foreach ([[$pa, $pb], [$pb, $pa]] as [$from, $to]) {
                    \App\Models\RelatedProduct::firstOrCreate([
                        'product_id' => $from->id,
                        'related_product_id' => $to->id,
                        'type' => 'bought_together',
                    ], ['score' => 99]);
                }
            }
        }
    }

    protected function scalePrice(float $base, int $grams, int $refGrams): float
    {
        return round($base * ($grams / max(1, $refGrams)), 0);
    }

    /** Generate an attractive SVG placeholder per product. */
    protected function makeProductSvg(Product $product): string
    {
        $palettes = [
            ['#E8F5E9', '#1B4332', '#74C69D'], ['#FFF3E0', '#E76F51', '#FFB703'],
            ['#E3F2FD', '#155E75', '#67E8F9'], ['#F3E5F5', '#581C87', '#D8B4FE'],
        ];
        [$bg, $fg, $accent] = $palettes[$product->id % count($palettes)];

        $initials = collect(explode(' ', $product->name))
            ->reject(fn ($w) => strtolower($w) === 'organic')
            ->map(fn ($w) => mb_substr($w, 0, 1))
            ->take(2)
            ->implode('');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <rect width="600" height="600" fill="{$bg}"/>
  <circle cx="300" cy="235" r="150" fill="{$accent}" opacity="0.35"/>
  <circle cx="300" cy="235" r="110" fill="{$accent}" opacity="0.55"/>
  <text x="300" y="262" font-family="Georgia, serif" font-size="96" font-weight="bold" fill="#ffffff" text-anchor="middle">{$initials}</text>
  <text x="300" y="430" font-family="Verdana, sans-serif" font-size="30" font-weight="600" fill="{$fg}" text-anchor="middle">{$product->name}</text>
  <text x="300" y="478" font-family="Verdana, sans-serif" font-size="22" fill="{$fg}" opacity="0.65" text-anchor="middle">AB Organic</text>
</svg>
SVG;

        $file = "products/{$product->slug}.svg";
        Storage::disk('public')->put($file, $svg);

        return 'storage/'.$file;
    }
}
