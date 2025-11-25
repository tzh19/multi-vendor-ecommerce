<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Inertia::share([
               'auth' => fn () => [
                   'user' => Auth::user(),
                   'role' => Auth::user()?->role,
               ],
               'cartCount' => function () {
                   $cart = session()->get('cart', []);
                   return array_sum(array_column($cart, 'quantity'));
               }
           ]);

    }
}
