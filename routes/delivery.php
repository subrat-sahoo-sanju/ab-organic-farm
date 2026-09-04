<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Delivery;

Route::prefix('portal')->name('delivery.')->middleware(['auth', 'active', 'role:delivery_person'])
    ->group(function () {
        Route::get('/dashboard', [Delivery\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/live', [Delivery\LiveController::class, 'dashboard'])->name('live');
        Route::get('/deliveries', [Delivery\DeliveryController::class, 'index'])->name('deliveries');
        Route::get('/deliveries/{assignment}', [Delivery\DeliveryController::class, 'show'])->name('show');
        Route::post('/deliveries/{assignment}/pickup', [Delivery\DeliveryController::class, 'pickedUp'])->name('pickup');
        Route::post('/deliveries/{assignment}/delivered', [Delivery\DeliveryController::class, 'delivered'])->name('delivered');
        Route::post('/deliveries/{assignment}/failed', [Delivery\DeliveryController::class, 'failed'])->name('failed');
        Route::get('/cod', [Delivery\CODController::class, 'index'])->name('cod');
        Route::get('/profile', [Delivery\ProfileController::class, 'show'])->name('profile');
        Route::post('/profile/toggle', [Delivery\ProfileController::class, 'toggleAvailability'])->name('profile.toggle');
    });
