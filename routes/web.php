<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\UserController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware(['auth', 'verified'])->group(function () {


    Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
            ->name('dashboard');
    });


    Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');

        // Optional: update order status
        Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    });


    Route::middleware(['role:admin,vendor'])
        ->group(function () {

            Route::get('users', function () {
                requireRole('admin');
                return app(\App\Http\Controllers\Admin\UserController::class)->index();
            })->name('users.index');


            Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

            Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

            Route::prefix('admin')->name('admin.')
                ->group(function () {
                    Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
                });


            Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);

        });


    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');

    Route::resource('cart', \App\Http\Controllers\CartController::class)
    ->only(['store', 'index', 'destroy']);

    Route::get('/checkout/success/{id}', [\App\Http\Controllers\CheckoutController::class, 'success'])->name('checkout.success');

    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/orders', [\App\Http\Controllers\CustomerOrderController::class, 'index'])->name('customer.orders.index');
    Route::get('/customer/orders/{order}', [\App\Http\Controllers\CustomerOrderController::class, 'show'])
       ->name('customer.orders.show');

    Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('customer.home.index');

    Route::get('/categories/{category}', [\App\Http\Controllers\CategoryController::class, 'show'])
        ->name('categories.show');

});






// Vendor
Route::middleware(['role:vendor'])->group(function () {
    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('dashboard', [ \App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name('dashboard');
    });

    Route::get('/vendor/orders', [\App\Http\Controllers\Vendor\VendorOrderController::class, 'index'])->name('vendor.orders.index');
    Route::get('/vendor/orders/{order}', [\App\Http\Controllers\Vendor\VendorOrderController::class, 'show'])->name('vendor.orders.show');

    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('dashboard', [ \App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name('dashboard');
    });

    Route::patch(
        '/vendor/orders/{order}/status',
        [\App\Http\Controllers\Vendor\VendorOrderController::class, 'updateStatus']
    )->name('vendor.orders.update-status');


});

// Customer
// Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
//     Route::get('/customer/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

require __DIR__.'/settings.php';
