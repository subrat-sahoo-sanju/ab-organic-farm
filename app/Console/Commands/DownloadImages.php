<?php

namespace App\Console\Commands;

use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Console\Command;

class DownloadImages extends Command
{
    protected $signature = 'images:download';

    protected $description = 'Download real organic product/category/brand images from free stock photo sites';

    private string $storagePath;
    private int $downloaded = 0;
    private int $fallbacks = 0;
    private int $errors = 0;

    private int $realDownloaded = 0;
    private int $betterSvgGenerated = 0;

    public function handle(): int
    {
        $this->storagePath = storage_path('app/public');
        $this->ensureDirectories();

        $this->line('--- Downloading product images from Unsplash ---');
        $this->downloadProductImages();

        $this->line('--- Downloading remaining 15 products (real images + better SVGs) ---');
        $this->downloadRemainingProducts();

        $this->line('--- Creating category images ---');
        $this->createCategoryImages();

        $this->line('--- Creating brand logos ---');
        $this->createBrandLogos();

        $this->newLine();
        $this->info("Done. Downloaded: {$this->downloaded}, Fallbacks: {$this->fallbacks}, Errors: {$this->errors}");
        $this->info("Remaining products - Real images: {$this->realDownloaded}, Better SVGs: {$this->betterSvgGenerated}");

        return Command::SUCCESS;
    }

    private function ensureDirectories(): void
    {
        foreach (['products', 'categories', 'brands'] as $dir) {
            $path = $this->storagePath . '/' . $dir;
            if (!is_dir($path)) {
                mkdir($path, 0755, true);
                $this->line("Created directory: {$dir}/");
            }
        }
    }

    /* ------------------------------------------------------------------
     |  Product Images
     | ------------------------------------------------------------------ */

    private function downloadProductImages(): void
    {
        $products = [
            'organic-basmati-rice'                  => 'photo-1586201375761-83865001e31c?w=600&h=600&fit=crop',
            'organic-whole-wheat-atta'              => 'photo-1574323347407-f5e1ad6d020b?w=600&h=600&fit=crop',
            'organic-turmeric-powder'               => 'photo-1615485500704-8e990f9900f7?w=600&h=600&fit=crop',
            'organic-red-chilli-powder'             => 'photo-1599909631498-711e4a6e7c2f?w=600&h=600&fit=crop',
            'organic-cumin-seeds'                   => 'photo-1596040033229-a9821ebd058d?w=600&h=600&fit=crop',
            'organic-garam-masala'                  => 'photo-1599909631498-711e4a6e7c2f?w=600&h=600&fit=crop',
            'organic-toor-dal'                      => 'photo-1612257416648-ee7a6c5b1e99?w=600&h=600&fit=crop',
            'organic-moong-dal'                     => 'photo-1612257416648-ee7a6c5b1e99?w=600&h=400&fit=crop',
            'organic-chana-dal'                     => 'photo-1612257416648-ee7a6c5b1e99?w=400&h=600&fit=crop',
            'organic-rajma-chitra'                  => 'photo-1612257416648-ee7a6c5b1e99?w=600&h=600&fit=crop&q=80',
            'organic-brown-rice'                    => 'photo-1586201375761-83865001e31c?w=600&h=400&fit=crop',
            'organic-red-rice'                      => 'photo-1586201375761-83865001e31c?w=400&h=600&fit=crop&q=80',
            'organic-quinoa'                        => 'photo-1586201375761-83865001e31c?w=600&h=600&fit=crop&q=90',
            'organic-foxtail-millet'                => 'photo-1586201375761-83865001e31c?w=500&h=500&fit=crop&q=80',
            'organic-almonds-california'            => 'photo-1508061253366-f7da158b6d44?w=600&h=600&fit=crop',
            'organic-golden-raisins'                => 'photo-1528735602780-2552fd46c7af?w=600&h=600&fit=crop',
            'organic-walnuts-kernel'                => 'photo-1563589178089-6e24be2b3a3e?w=600&h=600&fit=crop',
            'organic-peanut-butter-crunchy'         => 'photo-1612187209234-a06529ea13ed?w=600&h=600&fit=crop',
            'organic-coconut-oil'                   => 'photo-1621939514649-2308e6cc4b09?w=600&h=600&fit=crop',
            'wood-cold-pressed-groundnut-oil'       => 'photo-1474979266404-7eaacbcd87c5?w=600&h=600&fit=crop',
            'organic-a2-desi-cow-ghee'             => 'photo-1631452180519-c013f987d3c5?w=600&h=600&fit=crop',
            'organic-jaggery-powder'               => 'photo-1610139505121-5bbd12e45f1e?w=600&h=600&fit=crop',
            'organic-multigrain-roasted-chips'      => 'photo-1566478989037-eec170784d0b?w=600&h=600&fit=crop',
            'organic-tomatoes'                      => 'photo-1546470427-0d4db154ceb8?w=600&h=600&fit=crop',
            'organic-spinach'                       => 'photo-1576045057995-568f588f82fb?w=600&h=600&fit=crop',
            'farm-fresh-organic-bananas'            => 'photo-1571771894821-ce9b6c11b08e?w=600&h=600&fit=crop',
            'himalayan-organic-apples'              => 'photo-1560806887-1e4cd0b6cbd6?w=600&h=600&fit=crop',
            'organic-neem-tulsi-soap'               => 'photo-1600857544200-b2f666a9a2ec?w=600&h=600&fit=crop',
            'organic-dish-wash-liquid-neem'         => 'photo-1585421514284-efb74c2b69ba?w=600&h=600&fit=crop',
            'natural-handmade-herbal-shampoo-bar'   => 'photo-1607006344380-b6775a0824a7?w=600&h=600&fit=crop',
            'organic-ashwagandha-root-powder'       => 'photo-1610139505121-5bbd12e45f1e?w=600&h=400&fit=crop',
            'organic-triphala-churna'               => 'photo-1610139505121-5bbd12e45f1e?w=400&h=600&fit=crop',
        ];

        foreach ($products as $slug => $params) {
            $this->downloadSingleProduct($slug, $params);
        }
    }

    private function downloadSingleProduct(string $slug, string $params): void
    {
        $url = "https://images.unsplash.com/{$params}";
        $dest = "{$this->storagePath}/products/{$slug}.jpg";

        $this->line("  Downloading {$slug}...");

        $context = stream_context_create([
            'http' => [
                'timeout'  => 15,
                'header'   => "User-Agent: OrganicStoreBot/1.0\r\n",
            ],
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);

        $data = @file_get_contents($url, false, $context);

        if ($data !== false && strlen($data) > 1000) {
            file_put_contents($dest, $data);
            $this->downloaded++;

            ProductImage::where('path', 'like', "%{$slug}.svg%")
                ->update([
                    'path'      => "products/{$slug}.jpg",
                    'thumb_path' => "products/{$slug}.jpg",
                ]);

            $this->line("    <info>OK</info> (" . number_format(strlen($data) / 1024) . " KB)");
        } else {
            $this->line("    <comment>Download failed, generating fallback SVG</comment>");
            $this->fallbacks++;
            $this->createFallbackProductImage($slug);
        }
    }

    private function createFallbackProductImage(string $slug): void
    {
        $name = ucwords(str_replace('-', ' ', $slug));
        $colors = [
            'rice'    => ['#2d6a4f', '#52b788'],
            'wheat'   => ['#b08968', '#ddb892'],
            'turmeric'=> ['#e9c46a', '#f4a261'],
            'chilli'  => ['#e63946', '#f4845f'],
            'cumin'   => ['#606c38', '#283618'],
            'dal'     => ['#e9c46a', '#2a9d8f'],
            'rajma'   => ['#bc4749', '#a7c957'],
            'quinoa'  => ['#606c38', '#dda15e'],
            'millet'  => ['#bc6c25', '#dda15e'],
            'almond'  => ['#b08968', '#ddb892'],
            'raisin'  => ['#7b2d26', '#d4a373'],
            'walnut'  => ['#6b4226', '#d4a373'],
            'peanut'  => ['#b08968', '#ddb892'],
            'oil'     => ['#e9c46a', '#264653'],
            'ghee'    => ['#f4a261', '#e9c46a'],
            'jaggery' => ['#b08968', '#ddb892'],
            'chips'   => ['#606c38', '#283618'],
            'tomato'  => ['#e63946', '#f4845f'],
            'spinach' => ['#2d6a4f', '#52b788'],
            'banana'  => ['#e9c46a', '#f4a261'],
            'apple'   => ['#e63946', '#f4845f'],
            'soap'    => ['#2d6a4f', '#a7c957'],
            'dish'    => ['#264653', '#2a9d8f'],
            'shampoo' => ['#6b4226', '#d4a373'],
            'ashwa'   => ['#606c38', '#283618'],
            'triphala'=> ['#8b5e34', '#dda15e'],
        ];

        $fallback = ['#2d6a4f', '#52b788'];
        foreach ($colors as $key => $pair) {
            if (str_contains($slug, $key)) {
                $fallback = $pair;
                break;
            }
        }

        $svg = $this->makeProductSvg($name, $fallback[0], $fallback[1]);
        file_put_contents("{$this->storagePath}/products/{$slug}.svg", $svg);

        ProductImage::where('path', 'like', "%{$slug}.svg%")
            ->update([
                'path'      => "products/{$slug}.svg",
                'thumb_path' => "products/{$slug}.svg",
            ]);
    }

    private function makeProductSvg(string $name, string $c1, string $c2): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$c1};stop-opacity:1"/>
      <stop offset="100%" style="stop-color:{$c2};stop-opacity:1"/>
    </linearGradient>
  </defs>
  <rect width="600" height="600" fill="url(#bg)" rx="16"/>
  <text x="300" y="320" font-size="140" text-anchor="middle">&#127793;</text>
  <text x="300" y="540" font-family="sans-serif" font-size="14" fill="rgba(255,255,255,0.35)" text-anchor="middle">AB Organic Farm</text>
</svg>
SVG;
    }

    /* ------------------------------------------------------------------
     |  Remaining 15 Products – Real images + Better SVG fallback
     | ------------------------------------------------------------------ */

    private function downloadRemainingProducts(): void
    {
        $products = [
            'organic-jaggery-powder'         => 'jaggery',
            'organic-toor-dal'               => 'lentils',
            'organic-moong-dal'              => 'moong+dal',
            'organic-chana-dal'              => 'chickpeas',
            'organic-rajma-chitra'           => 'kidney+beans',
            'organic-red-chilli-powder'      => 'chili+powder',
            'organic-garam-masala'           => 'spices',
            'organic-a2-desi-cow-ghee'       => 'ghee+butter',
            'organic-coconut-oil'            => 'coconut+oil',
            'organic-tomatoes'               => 'tomatoes',
            'organic-almonds-california'     => 'almonds',
            'organic-walnuts-kernel'         => 'walnuts',
            'organic-peanut-butter-crunchy'  => 'peanut+butter',
            'organic-ashwagandha-root-powder'=> 'herbs+powder',
            'organic-triphala-churna'        => 'spices+herbs',
        ];

        $context = stream_context_create([
            'http' => [
                'timeout'  => 12,
                'header'   => "User-Agent: Mozilla/5.0 (compatible; OrganicStoreBot/1.0)\r\n",
                'follow_location' => true,
            ],
            'ssl' => [
                'verify_peer'      => false,
                'verify_peer_name' => false,
            ],
        ]);

        foreach ($products as $slug => $searchTerm) {
            $this->line("  Processing {$slug}...");

            $model = \App\Models\Product::where('slug', $slug)->first();
            if (!$model) {
                $this->warn("    Product not found in DB: {$slug}");
                $this->errors++;
                continue;
            }

            $jpgDest = "{$this->storagePath}/products/{$slug}.jpg";
            $gotReal = false;

            // Attempt 1: Unsplash source
            $unsplashUrl = "https://source.unsplash.com/featured/600x600/?" . urlencode($searchTerm);
            $data = @file_get_contents($unsplashUrl, false, $context);
            if ($data !== false && strlen($data) > 5120) {
                file_put_contents($jpgDest, $data);
                $this->line("    <info>Real image from Unsplash</info> (" . number_format(strlen($data) / 1024) . " KB)");
                $gotReal = true;
                $this->realDownloaded++;
            }

            // Attempt 2: Foodish API (only if attempt 1 failed)
            if (!$gotReal) {
                $categoryMap = [
                    'jaggery'   => 'rice',
                    'lentils'   => 'rice',
                    'chickpeas' => 'rice',
                    'beans'     => 'rice',
                    'spices'    => 'biryani',
                    'tomatoes'  => 'pizza',
                    'almonds'   => 'biryani',
                    'walnuts'   => 'biryani',
                ];
                $foodishCat = 'biryani';
                foreach ($categoryMap as $key => $cat) {
                    if (str_contains($searchTerm, $key)) {
                        $foodishCat = $cat;
                        break;
                    }
                }
                $foodishUrl = "https://foodish-api.com/api/images/{$foodishCat}";
                $data = @file_get_contents($foodishUrl, false, $context);
                if ($data !== false) {
                    $json = json_decode($data, true);
                    if (isset($json['image'])) {
                        $imgData = @file_get_contents($json['image'], false, $context);
                        if ($imgData !== false && strlen($imgData) > 5120) {
                            file_put_contents($jpgDest, $imgData);
                            $this->line("    <info>Real image from Foodish</info> (" . number_format(strlen($imgData) / 1024) . " KB)");
                            $gotReal = true;
                            $this->realDownloaded++;
                        }
                    }
                }
            }

            // Fallback: Generate a high-quality SVG
            if (!$gotReal) {
                $svgPath = "{$this->storagePath}/products/{$slug}.svg";
                $svg = $this->makeBetterProductSvg($model);
                file_put_contents($svgPath, $svg);
                $this->line("    <comment>Better SVG generated</comment>");
                $this->betterSvgGenerated++;
            }

            // Update database
            if ($gotReal) {
                ProductImage::where('path', 'like', "%{$slug}%")->update([
                    'path'      => "products/{$slug}.jpg",
                    'thumb_path' => "products/{$slug}.jpg",
                ]);
                // Remove old SVG if it exists
                $oldSvg = "{$this->storagePath}/products/{$slug}.svg";
                if (file_exists($oldSvg)) {
                    @unlink($oldSvg);
                }
            } else {
                ProductImage::where('path', 'like', "%{$slug}%")->update([
                    'path'      => "products/{$slug}.svg",
                    'thumb_path' => "products/{$slug}.svg",
                ]);
            }
        }
    }

    private function makeBetterProductSvg(\App\Models\Product $product): string
    {
        $palettes = [
            'dal'      => ['#FFF8E1', '#F57F17', '#FFD54F', '#FFB300'],
            'spice'    => ['#FBE9E7', '#BF360C', '#FF8A65', '#E64A19'],
            'grain'    => ['#EFEBE9', '#4E342E', '#A1887F', '#6D4C41'],
            'oil'      => ['#FFFDE7', '#F9A825', '#FFEE58', '#FBC02D'],
            'nut'      => ['#FFF3E0', '#E65100', '#FFB74D', '#FB8C00'],
            'fruit'    => ['#E8F5E9', '#2E7D32', '#81C784', '#43A047'],
            'veg'      => ['#E0F2F1', '#00695C', '#4DB6AC', '#00897B'],
            'care'     => ['#E8EAF6', '#283593', '#7986CB', '#3F51B5'],
            'wellness' => ['#F1F8E9', '#33691E', '#AED581', '#689F38'],
        ];

        $name = strtolower($product->name);
        $palette = $palettes['grain'];

        $paletteMap = [
            'dal'      => ['dal', 'toor', 'moong', 'chana', 'rajma'],
            'spice'    => ['chilli', 'masala', 'cumin', 'turmeric'],
            'grain'    => ['rice', 'atta', 'wheat'],
            'oil'      => ['oil', 'ghee'],
            'nut'      => ['almond', 'walnut', 'raisin', 'peanut'],
            'fruit'    => ['banana', 'apple', 'tomato', 'spinach'],
            'care'     => ['soap', 'shampoo', 'dish'],
            'wellness' => ['ashwagandha', 'triphala'],
        ];

        foreach ($paletteMap as $key => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($name, $needle)) {
                    $palette = $palettes[$key];
                    break 2;
                }
            }
        }

        [$bg, $fg, $accent, $light] = $palette;

        $emojiMap = [
            'dal'         => '&#127818;',
            'rajma'       => '&#127818;',
            'jaggery'     => '&#127855;',
            'chilli'      => '&#127798;',
            'masala'      => '&#129529;',
            'cumin'       => '&#129529;',
            'ghee'        => '&#129371;',
            'coconut'     => '&#129380;',
            'tomato'      => '&#127813;',
            'almond'      => '&#129361;',
            'walnut'      => '&#129361;',
            'peanut'      => '&#129361;',
            'ashwagandha' => '&#127807;',
            'triphala'    => '&#127807;',
        ];

        $emoji = '&#127793;';
        foreach ($emojiMap as $key => $code) {
            if (str_contains($name, $key)) {
                $emoji = $code;
                break;
            }
        }

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$bg}"/>
      <stop offset="100%" style="stop-color:{$light}"/>
    </linearGradient>
    <linearGradient id="circle" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$accent};stop-opacity:0.3"/>
      <stop offset="100%" style="stop-color:{$accent};stop-opacity:0.1"/>
    </linearGradient>
  </defs>
  <rect width="600" height="600" fill="url(#bg)" rx="16"/>
  <circle cx="300" cy="280" r="160" fill="url(#circle)"/>
  <circle cx="300" cy="280" r="120" fill="url(#circle)"/>
  <text x="300" y="320" font-size="140" text-anchor="middle">{$emoji}</text>
  <text x="300" y="540" font-family="'Segoe UI', Roboto, sans-serif" font-size="14" fill="{$fg}" opacity="0.35" text-anchor="middle">AB Organic Farm</text>
  <circle cx="80" cy="80" r="40" fill="{$accent}" opacity="0.1"/>
  <circle cx="520" cy="520" r="60" fill="{$accent}" opacity="0.08"/>
</svg>
SVG;
    }

    /* ------------------------------------------------------------------
     |  Category Images
     | ------------------------------------------------------------------ */

    private function createCategoryImages(): void
    {
        $categories = Category::whereNull('parent_id')->get();

        if ($categories->isEmpty()) {
            $this->warn('  No root categories found in database. Skipping category images.');
            return;
        }

        $categoryStyles = [
            'grains'     => ['grain' , '#2d6a4f', '#52b788', '&#127806;'],
            'spices'     => ['spice' , '#e9c46a', '#f4a261', '&#127798;'],
            'pulses'     => ['pulse' , '#606c38', '#283618', '&#127793;'],
            'nuts'       => ['nut'   , '#b08968', '#ddb892', '&#129385;'],
            'oils'       => ['oil'   , '#f4a261', '#e9c46a', '&#129388;'],
            'dairy'      => ['dairy' , '#a8dadc', '#457b9d', '&#129472;'],
            'sweeteners' => ['sweet' , '#bc6c25', '#dda15e', '&#127855;'],
            'snacks'     => ['snack' , '#e76f51', '#f4a261', '&#127838;'],
            'fresh'      => ['fresh' , '#2d6a4f', '#52b788', '&#127807;'],
            'personal'   => ['person', '#6b4226', '#d4a373', '&#128142;'],
            'ayurvedic'  => ['ayur'  , '#606c38', '#283618', '&#127811;'],
            'home'       => ['home'  , '#264653', '#2a9d8f', '&#127968;'],
        ];

        $defaults = ['#2d6a4f', '#52b788', '&#127807;'];
        $created = 0;

        foreach ($categories as $cat) {
            $slug = $cat->slug;
            $name = $cat->name;
            $icon = $cat->icon ?? '';

            $c1 = $defaults[0];
            $c2 = $defaults[1];
            $emoji = $defaults[2];

            foreach ($categoryStyles as $key => $style) {
                if (str_contains(strtolower($slug), $key) || str_contains(strtolower($name), $key)) {
                    $c1 = $style[1];
                    $c2 = $style[2];
                    $emoji = $style[3];
                    break;
                }
            }

            if (!empty($icon)) {
                $emoji = $icon;
            }

            $svg = $this->makeCategorySvg($name, $emoji, $c1, $c2);
            $path = "categories/{$slug}.svg";
            file_put_contents("{$this->storagePath}/categories/{$slug}.svg", $svg);

            Category::where('id', $cat->id)->update(['image_path' => $path]);
            $created++;
            $this->line("  Created: {$slug}.svg");
        }

        $this->info("  Created {$created} category images.");
    }

    private function makeCategorySvg(string $name, string $emoji, string $c1, string $c2): string
    {
        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300" viewBox="0 0 400 300">
  <defs>
    <linearGradient id="bg" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$c1};stop-opacity:1"/>
      <stop offset="100%" style="stop-color:{$c2};stop-opacity:1"/>
    </linearGradient>
  </defs>
  <rect width="400" height="300" rx="16" fill="url(#bg)"/>
  <text x="200" y="160" text-anchor="middle" font-size="80" fill="white">{$emoji}</text>
  <text x="200" y="270" text-anchor="middle" font-family="sans-serif" font-size="12" fill="rgba(255,255,255,0.45)">AB Organic Farm</text>
</svg>
SVG;
    }

    /* ------------------------------------------------------------------
     |  Brand Logos
     | ------------------------------------------------------------------ */

    private function createBrandLogos(): void
    {
        $brands = Brand::all();

        if ($brands->isEmpty()) {
            $this->warn('  No brands found in database. Skipping brand logos.');
            return;
        }

        $created = 0;

        foreach ($brands as $brand) {
            $slug = $brand->slug;
            $name = $brand->name;

            $svg = $this->makeBrandSvg($name);
            $path = "brands/{$slug}.svg";
            file_put_contents("{$this->storagePath}/brands/{$slug}.svg", $svg);

            Brand::where('id', $brand->id)->update(['logo_path' => $path]);
            $created++;
            $this->line("  Created: {$slug}.svg");
        }

        $this->info("  Created {$created} brand logos.");
    }

    private function makeBrandSvg(string $name): string
    {
        $escaped = htmlspecialchars($name, ENT_XML1);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="300" height="100" viewBox="0 0 300 100">
  <rect width="300" height="100" rx="8" fill="#ffffff" stroke="#e5e7eb" stroke-width="1"/>
  <line x1="20" y1="50" x2="26" y2="50" stroke="#2d6a4f" stroke-width="4" stroke-linecap="round"/>
  <text x="38" y="55" font-family="sans-serif" font-size="16" fill="#2d6a4f" font-weight="bold">{$escaped}</text>
  <text x="38" y="74" font-family="sans-serif" font-size="9" fill="#9ca3af">Certified Organic</text>
</svg>
SVG;
    }
}
