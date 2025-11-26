<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition()
    {
        $subtotal = $this->faker->randomFloat(2, 20, 500);
        $shipping_fee = $this->faker->randomFloat(2, 5, 20);

        return [
            'user_id'        => User::inRandomOrder()->first()->id ?? 1,
            'order_number'   => 'ORD-' . strtoupper(Str::random(10)),
            'subtotal'       => $subtotal,
            'shipping_fee'   => $shipping_fee,
            'total_price'    => $subtotal + $shipping_fee,
            'payment_method' => $this->faker->randomElement(['cod', 'card']),
            'address'        => $this->faker->address(),
            'status'         => 'processing',
            'payment_status' => 'pending',
        ];
    }
}
