<?php

use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->middleware(['auth', 'active'])
    ->group(function () {

        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])
            ->middleware('role:super_admin,admin,delivery_manager')
            ->name('dashboard');

        // Live admin notifications
        Route::get('/notifications', [Admin\NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications/fresh', [Admin\NotificationController::class, 'fresh'])->name('notifications.fresh');
        Route::post('/notifications/{notification}/read', [Admin\NotificationController::class, 'markRead'])->name('notifications.read');
        Route::post('/notifications/read-all', [Admin\NotificationController::class, 'markAllRead'])->name('notifications.read-all');

        Route::resource('categories', Admin\CategoryController::class)
            ->except('show')->middleware('role:super_admin,admin');
        Route::post('/categories/{category}/restore', [Admin\CategoryController::class, 'restore'])
            ->middleware('role:super_admin,admin')->name('categories.restore');

        Route::resource('brands', Admin\BrandController::class)
            ->only(['index', 'store', 'update', 'destroy'])->middleware('role:super_admin,admin');

        Route::get('/products/trashed', [Admin\ProductController::class, 'trashed'])->name('products.trashed');
        Route::post('/products/{product}/restore', [Admin\ProductController::class, 'restore'])->name('products.restore');
        Route::delete('/products/{product}/force', [Admin\ProductController::class, 'forceDelete'])->name('products.force-delete');
        Route::resource('products', Admin\ProductController::class)->except('show')->middleware('role:super_admin,admin');
        Route::post('/products/{product}/images', [Admin\ProductImageController::class, 'store'])->name('products.images.store');
        Route::post('/products/{product}/images/order', [Admin\ProductImageController::class, 'reorder'])->name('products.images.order');
        Route::delete('/images/{image}', [Admin\ProductImageController::class, 'destroy'])->name('images.destroy');
        Route::post('/variants/{variant}/default', [Admin\ProductVariantController::class, 'makeDefault'])->name('variants.default');

        Route::get('/inventory', [Admin\InventoryController::class, 'index'])->name('inventory.index');
        Route::get('/inventory/transactions', [Admin\InventoryController::class, 'transactions'])->name('inventory.transactions');
        Route::post('/inventory/{inventory}/adjust', [Admin\InventoryController::class, 'adjust'])->name('inventory.adjust');

        Route::get('/orders', [Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/live', [Admin\OrderController::class, 'live'])->name('orders.live');
        Route::get('/orders/{order}', [Admin\OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/transition', [Admin\OrderController::class, 'transition'])->name('orders.transition');

        Route::get('/delivery', [Admin\DeliveryController::class, 'index'])->name('delivery.index');
        Route::post('/orders/{order}/assign', [Admin\DeliveryController::class, 'assign'])->name('delivery.assign');
        Route::post('/assignments/{assignment}/reassign', [Admin\DeliveryController::class, 'reassign'])->name('delivery.reassign');
        Route::resource('delivery-persons', Admin\DeliveryPersonController::class)
            ->only(['index', 'store'])->middleware('role:super_admin,admin,delivery_manager');
        Route::post('/delivery-persons/{person}/toggle', [Admin\DeliveryPersonController::class, 'toggle'])->name('delivery-persons.toggle');

        Route::get('/banners', [Admin\BannersController::class, 'index'])->name('banners.index');
        Route::post('/banners', [Admin\BannersController::class, 'store'])->name('banners.store');
        Route::patch('/banners/{banner}', [Admin\BannersController::class, 'update'])->name('banners.update');
        Route::delete('/banners/{banner}', [Admin\BannersController::class, 'destroy'])->name('banners.destroy');
        Route::post('/banners/{banner}/toggle', [Admin\BannersController::class, 'toggle'])->name('banners.toggle');

        Route::get('/coupons', [Admin\CouponsController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [Admin\CouponsController::class, 'store'])->name('coupons.store');
        Route::patch('/coupons/{coupon}', [Admin\CouponsController::class, 'update'])->name('coupons.update');
        Route::delete('/coupons/{coupon}', [Admin\CouponsController::class, 'destroy'])->name('coupons.destroy');

        Route::get('/reviews', [Admin\ReviewsController::class, 'index'])->name('reviews.index');
        Route::post('/reviews/{review}/approve', [Admin\ReviewsController::class, 'approve'])->name('reviews.approve');
        Route::post('/reviews/{review}/reject', [Admin\ReviewsController::class, 'reject'])->name('reviews.reject');
        Route::delete('/reviews/{review}', [Admin\ReviewsController::class, 'destroy'])->name('reviews.destroy');

        Route::get('/customers', [Admin\CustomersController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [Admin\CustomersController::class, 'show'])->name('customers.show');
        Route::post('/customers/{customer}/block', [Admin\CustomersController::class, 'block'])->name('customers.block');
        Route::post('/customers/{customer}/unblock', [Admin\CustomersController::class, 'unblock'])->name('customers.unblock');

        Route::get('/staff', [Admin\StaffController::class, 'index'])->name('staff.index');
        Route::post('/staff', [Admin\StaffController::class, 'store'])->name('staff.store');
        Route::patch('/staff/{user}', [Admin\StaffController::class, 'update'])->name('staff.update');
        Route::post('/staff/{user}/toggle', [Admin\StaffController::class, 'toggle'])->name('staff.toggle');

        Route::get('/reports', [Admin\ReportsController::class, 'index'])->name('reports.index');

Route::get('/settings', [Admin\SettingsController::class, 'show'])->name('settings.show');
       Route::patch('/settings', [Admin\SettingsController::class, 'update'])->name('settings.update');
       Route::post('/settings/sections', [Admin\SettingsController::class, 'updateSections'])->name('settings.sections');
    });
