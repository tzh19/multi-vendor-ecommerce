<?php

namespace Tests\Feature\Vendor;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VendorDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_can_access_dashboard()
    {
        $vendor = User::factory()->create(['role' => 'vendor']);

        $response = $this->actingAs($vendor)->get(route('vendor.dashboard'));

        $response->assertStatus(200);
    }

    public function test_non_vendor_cannot_access_dashboard()
    {
        $customer = User::factory()->create(['role' => 'customer']);

        $response = $this->actingAs($customer)->get(route('vendor.dashboard'));

        $response->assertStatus(403);
    }
}
