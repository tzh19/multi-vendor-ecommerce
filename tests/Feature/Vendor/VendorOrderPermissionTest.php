<?php

namespace Tests\Feature\Vendor;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorOrderPermissionTest extends TestCase
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
            'price' => 100,
        ]);

        return [
            'vendorAUser' => $vendorAUser,
            'vendorA' => $vendorA,
            'productA' => $productA,
            'orderA' => $orderA,
            'vendorBUser' => $vendorBUser,
            'vendorB' => $vendorB,
            'productB' => $productB,
            'orderB' => $orderB,
        ];
    }


    public function test_vendor_can_see_only_their_orders()
    {

        $data = $this->createVendorsWithOrders();

        $response = $this->actingAs($data['vendorAUser'])->get(route('vendor.orders.index'));

        $response->assertInertia(
            fn ($page) =>
            $page->component('Vendor/Orders/Index')
                 ->where(
                     'orders.data',
                     fn ($orders) =>
                     collect($orders)->pluck('id')->contains($data['orderA']->id) &&
                     !collect($orders)->pluck('id')->contains($data['orderB']->id)
                 )
        );

    }

    public function test_vendor_cannot_see_other_vendor_orders()
    {
        $data = $this->createVendorsWithOrders();

        // Index check
        $response = $this->actingAs($data['vendorAUser'])
            ->get(route('vendor.orders.index'));

        // Ensure only vendorA's order appears
        $response->assertInertia(
            fn ($page) =>
            $page->where(
                'orders.data',
                fn ($orders) =>
                collect($orders)->pluck('id')->contains($data['orderA']->id) &&
                !collect($orders)->pluck('id')->contains($data['orderB']->id)
            )
        );

        // Show check
        $this->actingAs($data['vendorAUser'])
            ->get(route('vendor.orders.show', $data['orderB']))
            ->assertStatus(403); // cannot access orderB (vendorB's order)

    }

    public function test_vendor_cannot_update_other_vendors_order()
    {
        $data = $this->createVendorsWithOrders();

        $this->actingAs($data['vendorAUser'])
             ->patch(route('vendor.orders.update-status', $data['orderB']), ['status' => 'shipped'])
             ->assertStatus(403);
    }
}
