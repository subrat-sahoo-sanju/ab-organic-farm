<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\ProductImage;

$context = stream_context_create([
    'http' => ['timeout' => 15, 'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36\r\n", 'follow_location' => true],
    'ssl' => ['verify_peer' => false, 'verify_peer_name' => false],
]);

$products = [
    'organic-toor-dal' => ['photo-1612257416648-ee7a6c5b1e99', 'photo-1600733522897-7b9b30f19a92'],
    'organic-moong-dal' => ['photo-1612257416648-ee7a6c5b1e99', 'photo-1600733522897-7b9b30f19a92'],
    'organic-chana-dal' => ['photo-1612257416648-ee7a6c5b1e99', 'photo-1600733522897-7b9b30f19a92'],
    'organic-rajma-chitra' => ['photo-1508589788843-8acb6b1d208a', 'photo-1600733522897-7b9b30f19a92'],
    'organic-tomatoes' => ['photo-1546470427-0d4db154ceb8', 'photo-1592841200221-5d1b1c4c5f4a'],
    'organic-almonds-california' => ['photo-1508061253366-f7da158b6d44', 'photo-1563589178089-6e24be2b3a3e'],
    'organic-walnuts-kernel' => ['photo-1563589178089-6e24be2b3a3e', 'photo-1508061253366-f7da158b6d44'],
    'organic-peanut-butter-crunchy' => ['photo-1521519263451-2754fa99a025', 'photo-1612187209234-a06529ea13ed'],
    'organic-flax-seeds' => ['photo-1509622905150-fa66d392f267', 'photo-1599706200160-8d7c7b4e7c15'],
    'organic-pumpkin-seeds' => ['photo-1599706200160-8d7c7b4e7c15', 'photo-1509622905150-fa66d392f267'],
    'organic-neem-powder' => ['photo-1610139505121-5bbd12e45f1e', 'photo-1599909631498-711e4a6e7c2f'],
];

$converted = 0;
foreach ($products as $slug => $photoIds) {
    $success = false;
    foreach ($photoIds as $photoId) {
        $url = 'https://images.unsplash.com/' . $photoId . '?w=600&h=600&fit=crop&q=80';
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
            echo "Converted: $slug (via $photoId)\n";
            $success = true;
            break;
        }
    }
    if (!$success) {
        echo "Failed: $slug\n";
    }
}
echo "Total converted: $converted\n";