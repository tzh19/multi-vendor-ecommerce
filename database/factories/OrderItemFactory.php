<?php

namespace Database\Factories;

use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition()
    {
        $product = Product::inRandomOrder()->first();
        $quantity = $this->faker->numberBetween(1, 5);

        return [
            'order_id'   => Order::inRandomOrder()->first()->id ?? 1,
            'product_id' => $product->id ?? 1,
            'quantity'   => $quantity,
            'price'      => $product->price ?? 50,
        ];
    }
}
