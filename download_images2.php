<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductImage;

$context = stream_context_create([
    'http' => ['timeout' => 10, 'header' => "User-Agent: Mozilla/5.0\r\n"],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
]);

// Updated with more reliable Unsplash photo IDs
$products = [
    'organic-jaggery-powder' => 'photo-1587049352846-4a222e784d38?w=600&h=600&fit=crop',
    'organic-toor-dal' => 'photo-1600733522897-7b9b30f19a92?w=600&h=600&fit=crop',
    'organic-moong-dal' => 'photo-1600733522897-7b9b30f19a92?w=600&h=600&fit=crop',
    'organic-chana-dal' => 'photo-1600733522897-7b9b30f19a92?w=600&h=600&fit=crop',
    'organic-rajma-chitra' => 'photo-1508589788843-8acb6b1d208a?w=600&h=600&fit=crop',
    'organic-red-chilli-powder' => 'photo-1596040033229-a9821ebd058d?w=600&h=600&fit=crop',
    'organic-a2-desi-cow-ghee' => 'photo-1589985270826-4b7bb135bc9d?w=600&h=600&fit=crop',
    'organic-coconut-oil' => 'photo-1474979266404-7eaacbcd87c5?w=600&h=600&fit=crop',
    'organic-tomatoes' => 'photo-1546470427-0d4db154ceb8?w=600&h=600&fit=crop',
    'organic-almonds-california' => 'photo-1508061253366-f7da158b6d44?w=600&h=600&fit=crop',
    'organic-walnuts-kernel' => 'photo-1563589178089-6e24be2b3a3e?w=600&h=600&fit=crop',
    'organic-peanut-butter-crunchy' => 'photo-1521519263451-2754fa99a025?w=600&h=600&fit=crop',
    'organic-flax-seeds' => 'photo-1509622905150-fa66d392f267?w=600&h=600&fit=crop',
    'organic-pumpkin-seeds' => 'photo-1599706200160-8d7c7b4e7c15?w=600&h=600&fit=crop',
    'organic-neem-powder' => 'photo-1610139505121-5bbd12e45f1e?w=600&h=600&fit=crop',
    'organic-curry-leaves-powder' => 'photo-1596040033229-a9821ebd058d?w=600&h=600&fit=crop',
];

$converted = 0;
foreach ($products as $slug => $photoId) {
    $url = 'https://images.unsplash.com/' . $photoId;
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