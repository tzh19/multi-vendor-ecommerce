<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminProductPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function createProducts()
    {

        $vendorAUser = User::factory()->create(['role' => 'vendor']);
        $vendorA = Vendor::factory()->create(['user_id' => $vendorAUser->id]);
        $categoryA = Category::factory()->create();
        $productA = Product::factory()->create([
            'vendor_id' => $vendorA->id,
            'category_id' => $categoryA->id,
        ]);



        $vendorBUser = User::factory()->create(['role' => 'vendor']);
        $vendorB = Vendor::factory()->create(['user_id' => $vendorBUser->id]);
        $categoryB = Category::factory()->create();
        $productB = Product::factory()->create([
            'vendor_id' => $vendorB->id,
            'category_id' => $categoryB->id,
        ]);


        return compact(
            'vendorAUser',
            'vendorA',
            'productA',
            'vendorBUser',
            'vendorB',
            'productB',
        );
    }
    public function test_admin_can_see_all_products()
    {
        $data = $this->createProducts();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.products.index'))
            ->assertInertia(
                fn ($page) =>
                $page->where(
                    'products.data',
                    fn ($products) =>
                    collect($products)->pluck('id')->contains($data['productA']->id)
                    && collect($products)->pluck('id')->contains($data['productB']->id)
                )
            );
    }

    public function test_admin_can_create_product()
    {

        $user = User::factory()->create(['role' => 'admin']);

        // Create a user with vendor role
        $vendorUser = User::factory()->create(['role' => 'vendor']);

        // Create a vendor record for this user
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);

        $category = Category::factory()->create();

        $this->actingAs($user)
             ->post(route('admin.products.store'), [
                 'name' => 'Test Product',
                 'description' => 'Test description',
                 'price' => 100,
                 'stock' => 10,
                 'category_id' => $category->id,
                 'vendor_id' => $vendor->id
             ])
             ->assertRedirect(route('admin.products.index'));

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'vendor_id' => $vendor->id,
        ]);
    }

    public function test_admin_can_update_any_product()
    {

        $user = User::factory()->create(['role' => 'admin']);

        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
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

    public function test_admin_can_delete_any_product()
    {

        $user = User::factory()->create(['role' => 'admin']);

        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
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
}
