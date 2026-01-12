<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Laravel\Fortify\Features;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered()
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

    // public function test_users_with_two_factor_enabled_are_redirected_to_two_factor_challenge()
    // {
    //     if (! Features::canManageTwoFactorAuthentication()) {
    //         $this->markTestSkipped('Two-factor authentication is not enabled.');
    //     }

    //     Features::twoFactorAuthentication([
    //         'confirm' => true,
    //         'confirmPassword' => true,
    //     ]);

    //     $user = User::factory()->create();

    //     $user->forceFill([
    //         'two_factor_secret' => encrypt('test-secret'),
    //         'two_factor_recovery_codes' => encrypt(json_encode(['code1', 'code2'])),
    //         'two_factor_confirmed_at' => now(),
    //     ])->save();

    //     $response = $this->post(route('login'), [
    //         'email' => $user->email,
    //         'password' => 'password',
    //     ]);

    //     $response->assertRedirect(route('two-factor.login'));
    //     $response->assertSessionHas('login.id', $user->id);
    //     $this->assertGuest();
    // }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        $user = User::factory()->create();

        $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirect(route('home'));
    }

    public function test_users_are_rate_limited()
    {
        $user = User::factory()->create();

        RateLimiter::increment(md5('login'.implode('|', [$user->email, '127.0.0.1'])), amount: 5);

        $response = $this->post(route('login.store'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertTooManyRequests();
    }
}
