<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_admin_is_redirected_to_admin_dashboard(): void
    {
        $admin = User::factory()->create([
            'role' => 'admin',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('dashboard'));
    }

    public function test_vendor_is_redirected_to_vendor_dashboard(): void
    {
        $vendor = User::factory()->create([
            'role' => 'vendor',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $vendor->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($vendor);
        $response->assertRedirect(route('vendor.dashboard'));
    }

    public function test_customer_is_redirected_to_home(): void
    {
        $customer = User::factory()->create([
            'role' => 'customer',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $customer->email,
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($customer);
        $response->assertRedirect(route('products.index'));
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
        $response->assertSessionHasErrors('email');
    }


    public function test_login_requires_email_and_password(): void
    {
        $response = $this->post(route('login'), []);

        $response->assertSessionHasErrors(['email', 'password']);
    }

}
