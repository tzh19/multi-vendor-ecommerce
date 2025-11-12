<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cart>
 */
class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'user_id' => null,
           'product_id' => null,
           'quantity' => $this->faker->numberBetween(0, 10),
           'created_at' => now(),
           'updated_at' => now(),
        ];
    }
}
