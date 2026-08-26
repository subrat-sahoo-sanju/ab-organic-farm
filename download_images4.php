<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductImage;

$context = stream_context_create([
    'http' => ['timeout' => 15, 'header' => "User-Agent: Mozilla/5.0\r\n", 'follow_location' => true],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
]);

// Try Pexels API (free, no key needed for small usage) or Unsplash Source
$products = [
    'organic-toor-dal' => 'https://source.unsplash.com/featured/600x600/?lentils,dal,organic',
    'organic-moong-dal' => 'https://source.unsplash.com/featured/600x600/?mung+beans,dal,organic',
    'organic-chana-dal' => 'https://source.unsplash.com/featured/600x600/?chickpeas,dal,organic',
    'organic-rajma-chitra' => 'https://source.unsplash.com/featured/600x600/?kidney+beans,organic',
    'organic-tomatoes' => 'https://source.unsplash.com/featured/600x600/?tomatoes,organic,fresh',
    'organic-almonds-california' => 'https://source.unsplash.com/featured/600x600/?almonds,organic,nuts',
    'organic-walnuts-kernel' => 'https://source.unsplash.com/featured/600x600/?walnuts,organic,nuts',
    'organic-peanut-butter-crunchy' => 'https://source.unsplash.com/featured/600x600/?peanut+butter,organic',
    'organic-flax-seeds' => 'https://source.unsplash.com/featured/600x600/?flax+seeds,organic',
    'organic-pumpkin-seeds' => 'https://source.unsplash.com/featured/600x600/?pumpkin+seeds,organic',
    'organic-neem-powder' => 'https://source.unsplash.com/featured/600x600/?neem+powder,herbal,organic',
];

$converted = 0;
foreach ($products as $slug => $url) {
    $data = @file_get_contents($url, false, $context);
    if ($data !== false && strlen($data) > 1024) {
        $dest = storage_path('app/public/products/' . $slug . '.jpg');
        file_put_contents($dest, $data);
        ProductImage::where('path', 'like', '%' . $slug . '%')->update([
            'path' => 'products/' . $slug . '.jpg',
            'thumb_path' => 'products/' . $slug . '.jpg',
        ]);
        @unlink(storage_path('app/public/products/' . $slug . '.svg'));
        $converted++;
        echo "Converted: $slug\n";
    } else {
        echo "Failed: $slug\n";
    }
}
echo "Total converted: $converted\n";