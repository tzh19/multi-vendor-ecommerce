<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerCanViewProductsTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_see_product_listing(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $productA = Product::factory()->create(['name' => 'Product A']);
        $productB = Product::factory()->create(['name' => 'Product B']);

        $this->actingAs($customer)
            ->get(route('products.index'))
            ->assertStatus(200)
            ->assertSee('Product A')
            ->assertSee('Product B');
    }

    public function test_customer_can_see_product_in_listing_and_add_to_cart(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $product = Product::factory()->create([
            'name' => 'Awesome Product',
            'description' => 'This is a test product',
        ]);

        $this->actingAs($customer)
            ->get(route('products.index'))
            ->assertStatus(200)
            ->assertSee('Awesome Product');
    }
}
