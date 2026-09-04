<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;

// Serve CSS/JS through PHP to bypass ModSecurity file-type blocks on cPanel
Route::get('/assets/app.css', [AssetController::class, 'css'])->name('asset.css');
Route::get('/assets/app.js', [AssetController::class, 'js'])->name('asset.js');

require __DIR__.'/auth.php';
require __DIR__.'/customer.php';
require __DIR__.'/admin.php';
require __DIR__.'/delivery.php';
