<?php

use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

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
        requireRole('admin');

        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('users', [UserController::class, 'index'])->name('users.index');

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
