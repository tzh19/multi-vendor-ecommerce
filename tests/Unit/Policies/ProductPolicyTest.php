<?php

namespace Tests\Unit\Policies;

use App\Models\User;
use App\Models\Product;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_update_own_product()
    {
        ['vendorUser' => $vendorUser, 'product' => $product] = $this->createVendorWithProduct();

        $this->assertTrue(
            $vendorUser->can('update', $product)
        );
    }

    public function test_vendor_cannot_update_other_vendors_product()
    {
        ['vendorUser' => $owner, 'product' => $product] = $this->createVendorWithProduct();
        ['user' => $otherVendor] = $this->createOtherVendor();

        $this->assertFalse(
            $otherVendor->can('update', $product)
        );
    }

    public function test_vendor_can_delete_own_product()
    {
        ['vendorUser' => $vendorUser, 'product' => $product] = $this->createVendorWithProduct();

        $this->assertTrue(
            $vendorUser->can('delete', $product)
        );
    }

    public function test_vendor_can_view_own_product()
    {
        ['vendorUser' => $vendorUser, 'product' => $product] = $this->createVendorWithProduct();

        $this->assertTrue(
            $vendorUser->can('view', $product)
        );
    }

    public function test_vendor_cannot_view_other_vendors_product()
    {
        ['vendorUser' => $owner, 'product' => $product] = $this->createVendorWithProduct();
        ['user' => $otherVendor] = $this->createOtherVendor();

        $this->assertFalse(
            $otherVendor->can('view', $product)
        );
    }

    public function test_vendor_can_create_product()
    {
        ['vendorUser' => $vendorUser, 'product' => $product] = $this->createVendorWithProduct();

        $this->assertTrue(
            $vendorUser->can('view', $product)
        );
    }

    public function test_admin_can_update_any_product()
    {
        ['adminUser' => $adminUser, 'product' => $product] = $this->createAdminWithProduct();

        $this->assertTrue(
            $adminUser->can('update', $product)
        );
    }

    public function test_admin_can_delete_any_product()
    {
        ['adminUser' => $adminUser, 'product' => $product] = $this->createAdminWithProduct();

        $this->assertTrue(
            $adminUser->can('delete', $product)
        );
    }

    private function createVendorWithProduct(): array
    {
        $vendorUser = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create([
            'user_id' => $vendorUser->id,
        ]);

        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
        ]);

        return compact('vendorUser', 'vendor', 'product');
    }

    private function createOtherVendor(): array
    {
        $user = User::factory()->create(['role' => 'vendor']);
        $vendor = Vendor::factory()->create([
            'user_id' => $user->id,
        ]);

        return compact('user', 'vendor');
    }

    private function createAdminWithProduct(): array
    {
        $adminUser = User::factory()->create(['role' => 'admin']);
        $vendorUser = User::factory()->create(['role' => 'vendor']);

        $vendor = Vendor::factory()->create([
            'user_id' => $vendorUser->id,
        ]);

        $product = Product::factory()->create([
            'vendor_id' => $vendor->id,
        ]);

        return compact('adminUser', 'vendor', 'product');
    }

}
