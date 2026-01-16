<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminOrderPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorsWithOrders()
    {
        // Vendor A
        $vendorAUser = User::factory()->create(['role' => 'vendor']);
        $vendorA = Vendor::factory()->create(['user_id' => $vendorAUser->id]);
        $productA = Product::factory()->create(['vendor_id' => $vendorA->id]);

        $orderA = Order::factory()->create();
        $orderA->items()->create([
            'product_id' => $productA->id,
            'quantity' => 1,
            'price' => 100,
        ]);

        // Vendor B
        $vendorBUser = User::factory()->create(['role' => 'vendor']);
        $vendorB = Vendor::factory()->create(['user_id' => $vendorBUser->id]);
        $productB = Product::factory()->create(['vendor_id' => $vendorB->id]);

        $orderB = Order::factory()->create();
        $orderB->items()->create([
            'product_id' => $productB->id,
            'quantity' => 1,
            'price' => 200,
        ]);

        return compact(
            'vendorAUser',
            'vendorA',
            'productA',
            'orderA',
            'vendorBUser',
            'vendorB',
            'productB',
            'orderB'
        );
    }

    public function test_admin_can_see_all_orders()
    {
        $data = $this->createVendorsWithOrders();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.orders.index'))
            ->assertInertia(
                fn ($page) =>
                $page->where(
                    'orders.data',
                    fn ($orders) =>
                    collect($orders)->pluck('id')->contains($data['orderA']->id)
                    && collect($orders)->pluck('id')->contains($data['orderB']->id)
                )
            );
    }

    public function test_admin_can_view_any_order()
    {
        $data = $this->createVendorsWithOrders();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $data['orderA']))
            ->assertStatus(200);

        $this->actingAs($admin)
            ->get(route('admin.orders.show', $data['orderB']))
            ->assertStatus(200);
    }


    public function test_admin_can_update_any_order_status()
    {
        $data = $this->createVendorsWithOrders();
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->patch(route('admin.orders.update-status', $data['orderA']), [
                'status' => 'completed',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'id' => $data['orderA']->id,
            'status' => 'completed',
        ]);
    }


}
