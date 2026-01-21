<?php

namespace Tests\Feature\Customer;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerCartTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_add_product_to_cart(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $product = Product::factory()->create(['stock' => 10]);

        $this->actingAs($customer)
            ->post(route('cart.store', $product), [
                'product_id' => $product->id
                ])
            ->assertRedirect();

        $this->assertDatabaseCount('carts', 1);

        $this->assertDatabaseHas('carts', [
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 1,
        ]);

    }

    public function test_customer_can_remove_product_from_cart(): void
    {

        $customer = User::factory()->create(['role' => 'customer']);
        $product  = Product::factory()->create(['stock' => 10]);

        $cart = Cart::create([
            'user_id' => $customer->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);


        $this->actingAs($customer)
                ->delete(route('cart.destroy', $cart->id))
                ->assertRedirect(route('cart.index'));

        $this->assertSoftDeleted('carts', [
            'id' => $cart->id,
        ]);

    }

    public function test_cart_total_is_calculated_correctly(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $productA = Product::factory()->create(['price' => 100, 'stock' => 10]);
        $productB = Product::factory()->create(['price' => 50, 'stock' => 10]);

        // Create cart records directly
        Cart::create([
            'user_id' => $customer->id,
            'product_id' => $productA->id,
            'quantity' => 2,
        ]);

        Cart::create([
            'user_id' => $customer->id,
            'product_id' => $productB->id,
            'quantity' => 3,
        ]);

        // Fetch cart from DB including product price
        $cartItems = Cart::with('product')
            ->where('user_id', $customer->id)
            ->get();

        $total = 0;
        foreach ($cartItems as $item) {
            $total += $item->quantity * $item->product->price;
        }

        $this->assertEquals(2 * 100 + 3 * 50, $total);
    }

}
