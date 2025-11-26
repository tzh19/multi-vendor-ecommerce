<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        // Create 2-5 items per order
        $orders = \App\Models\Order::all();

        foreach ($orders as $order) {
            \App\Models\Product::inRandomOrder()->take(rand(2, 5))->get()->each(function ($product) use ($order) {
                OrderItem::factory()->create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                ]);
            });
        }
    }
}
