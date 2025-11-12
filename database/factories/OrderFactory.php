<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $status = ['pending', 'confirmed', 'delivered', 'canceled'];
        $payment_status = ['pending', 'paid'];

        return [
           'user_id' => User::factory(),
           'status' => $this->faker->randomElement($status),
           'total_price' => $this->faker->randomFloat(2, 10, 500), // 10.00 - 500.00
           'payment_status' => $this->faker->randomElement($payment_status),
           'created_at' => now(),
           'updated_at' => now(),
        ];
    }
}
