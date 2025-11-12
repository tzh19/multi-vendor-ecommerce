<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class CartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $users = User::where('role', 'customer')->get();
        $products = Product::all();

        Cart::factory()->count(10)->make()->each(function ($cart) use ($users, $products) {
            $cart->user_id = $users->random()->id;
            $cart->product_id = $products->random()->id;
            $cart->save();
        });

    }
}
