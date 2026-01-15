<?php

namespace Tests\Feature\Vendor;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorProductPermissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_create_product()
    {
        // Create a user with vendor role
        $user = User::factory()->create(['role' => 'vendor']);

        // Create a vendor record for this user
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);

        $category = Category::factory()->create();

        $this->actingAs($user)
             ->post(route('admin.products.store'), [
                 'name' => 'Test Product',
                 'description' => 'Test description',
                 'price' => 100,
                 'stock' => 10,
                 'category_id' => $category->id,
             ])
             ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'vendor_id' => $vendor->id,
        ]);
    }

    public function test_vendor_can_edit_own_product()
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create();
        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)
             ->put(route('admin.products.update', $product), [
                 'name' => 'Updated Product',
                 'description' => 'Updated description',
                 'price' => 200,
                 'stock' => 5,
                 'category_id' => $category->id,
                 'vendor_id' => $vendor->id,
             ])
             ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'name' => 'Updated Product',
            'price' => 200,
        ]);
    }

    public function test_vendor_can_delete_own_product()
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $user->setRelation('vendor', $vendor); // ✅ important

        $category = Category::factory()->create();

        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
            'category_id' => $category->id,
        ]);

        $this->actingAs($user)
            ->delete(route('admin.products.destroy', $product->id))
            ->assertRedirect(route('admin.products.index'));

        $this->assertSoftDeleted('products', [
            'id' => $product->id,
        ]);

    }

    public function test_vendor_cannot_edit_another_vendors_product()
    {
        $vendor1User = User::factory()->create(['role' => 'vendor']);
        $vendor1 = Vendor::factory()->create(['user_id' => $vendor1User->id]);

        $vendor2User = User::factory()->create(['role' => 'vendor']);
        $vendor2 = Vendor::factory()->create(['user_id' => $vendor2User->id]);

        $product = Product::factory()->create(['vendor_id' => $vendor2->id]);

        $this->actingAs($vendor1User)
             ->get(route('admin.products.edit', $product))
             ->assertStatus(403);
    }

    public function test_vendor_cannot_delete_another_vendors_product()
    {
        $vendor1User = User::factory()->create(['role' => 'vendor']);
        $vendor1 = Vendor::factory()->create(['user_id' => $vendor1User->id]);

        $vendor2User = User::factory()->create(['role' => 'vendor']);
        $vendor2 = Vendor::factory()->create(['user_id' => $vendor2User->id]);

        $product = Product::factory()->create(['vendor_id' => $vendor2->id]);

        $this->actingAs($vendor1User)
             ->delete(route('admin.products.destroy', $product))
             ->assertStatus(403);
    }
}
