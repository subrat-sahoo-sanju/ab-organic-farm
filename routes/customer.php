<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer;

Route::get('/', [Customer\HomeController::class, 'index'])->name('shop.index');
Route::get('/categories/all', [Customer\CategoryController::class, 'all'])->name('shop.categories');
Route::get('/categories/{category:slug}', [Customer\CategoryController::class, 'show'])->name('shop.category');
Route::get('/products/{product:slug}', [Customer\ProductController::class, 'show'])->name('shop.product');
Route::get('/search', [Customer\SearchController::class, 'results'])->name('shop.search');
Route::get('/search-page', [Customer\SearchController::class, 'page'])->name('shop.search-page');
Route::get('/api/search-suggest', [Customer\SearchController::class, 'suggest'])->name('shop.search-suggest');
Route::get('/api/pincode/{pincode}/check', [Customer\DeliveryAreaController::class, 'check'])->name('pincode.check');
Route::get('/api/brands/{brand}/products', [Customer\HomeController::class, 'brandProductsApi'])->name('api.brand.products');
Route::get('/api/categories/{category}/products', [Customer\HomeController::class, 'categoryProductsApi'])->name('api.category.products');
Route::get('/api/welcome-tab/products', [Customer\HomeController::class, 'welcomeTabApi'])->name('api.welcome-tab');

Route::get('/cart', [Customer\CartController::class, 'index'])->name('cart.index');
Route::get('/cart/state', [Customer\CartController::class, 'state'])->name('cart.state');
Route::post('/cart/add', [Customer\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/items/{item}', [Customer\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/items/{item}', [Customer\CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [Customer\CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
Route::delete('/cart/coupon', [Customer\CartController::class, 'removeCoupon'])->name('cart.remove-coupon');

Route::get('/wishlist/{product}/toggle', [Customer\WishlistController::class, 'toggle'])
    ->middleware('auth')->name('wishlist.toggle');
Route::get('/wishlist', [Customer\WishlistController::class, 'index'])
    ->middleware(['auth', 'active'])->name('account.wishlist');

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/checkout', [Customer\CheckoutController::class, 'show'])->name('checkout');
    Route::post('/checkout/place-order', [Customer\CheckoutController::class, 'placeOrder'])->name('checkout.place');
    Route::post('/checkout/add-address', [Customer\CheckoutController::class, 'addAddress'])->name('checkout.add-address');

    Route::prefix('account')->name('account.')->group(function () {
        Route::get('/', [Customer\AccountController::class, 'dashboard'])->name('dashboard');
        Route::patch('/profile', [Customer\AccountController::class, 'updateProfile'])->name('profile');
        Route::put('/password', [Customer\AccountController::class, 'updatePassword'])->name('password');

        Route::get('/orders', [Customer\OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{order}', [Customer\OrderController::class, 'show'])->name('orders.show');
        Route::delete('/orders/{order}/cancel', [Customer\OrderController::class, 'cancel'])->name('orders.cancel');
        Route::post('/orders/{order}/reorder', [Customer\OrderController::class, 'reorder'])->name('orders.reorder');

        Route::get('/addresses', [Customer\AddressController::class, 'index'])->name('addresses');
        Route::post('/addresses', [Customer\AddressController::class, 'store'])->name('addresses.store');
        Route::patch('/addresses/{address}', [Customer\AddressController::class, 'update'])->name('addresses.update');
        Route::delete('/addresses/{address}', [Customer\AddressController::class, 'destroy'])->name('addresses.destroy');
        Route::post('/addresses/{address}/default', [Customer\AddressController::class, 'setDefault'])->name('addresses.default');

        Route::get('/notifications', [Customer\NotificationController::class, 'index'])->name('notifications');
        Route::post('/notifications/mark-all-read', [Customer\NotificationController::class, 'markAllRead'])->name('notifications.mark-all');
    });

Route::post('/reviews', [Customer\ReviewController::class, 'store'])->name('reviews.store');
});

// Guest request endpoints (notify-me, newsletter)
Route::post('/notify-me', [Customer\NotificationRequestController::class, 'notifyMe'])->name('notify-me');
Route::post('/newsletter', [Customer\NotificationRequestController::class, 'newsletter'])->name('newsletter');
