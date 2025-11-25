<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Admin


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {

        $user = auth()->user();

        $props = [
            'isAdmin' => $user->hasRole('admin'),
            'isVendor' => $user->hasRole('vendor'),
            'isCustomer' => $user->hasRole('customer'),
        ];

        return Inertia::render('Dashboard', $props);
    })->name('dashboard');

    Route::get('users', function () {
        requireRole('admin');
        return app(\App\Http\Controllers\Admin\UserController::class)->index();
    })->name('users.index');

    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);


    Route::get('/products', [\App\Http\Controllers\ProductController::class, 'index'])->name('products.index');


    Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);

    Route::prefix('admin')->name('admin.')
    ->group(function () {
        Route::resource('products', \App\Http\Controllers\Admin\ProductController::class);
    });

    Route::prefix('vendor')->name('vendor.')->group(function () {
        Route::get('dashboard', [ \App\Http\Controllers\Vendor\VendorDashboardController::class, 'index'])->name('dashboard');
    });

    Route::resource('cart', \App\Http\Controllers\CartController::class)
    ->only(['store', 'index', 'destroy']);
});



// Vendor
// Route::middleware(['auth', 'verified', 'role:vendor'])->group(function () {
//     Route::get('/vendor/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

// Customer
// Route::middleware(['auth', 'verified', 'role:customer'])->group(function () {
//     Route::get('/customer/dashboard', function () {
//         return Inertia::render('Dashboard');
//     })->name('dashboard');
// });

require __DIR__.'/settings.php';
