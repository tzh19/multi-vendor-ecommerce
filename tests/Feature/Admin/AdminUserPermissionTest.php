<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUserPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function createUsers()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $vendor = User::factory()->create(['role' => 'vendor']);
        $customer = User::factory()->create(['role' => 'customer']);

        return compact('admin', 'vendor', 'customer');
    }

    public function test_admin_can_view_users()
    {
        $data = $this->createUsers();

        $this->actingAs($data['admin'])
            ->get(route('users.index'))
            ->assertStatus(200);
    }

    public function test_admin_can_update_user_role()
    {
        $data = $this->createUsers();

        $this->actingAs($data['admin'])
            ->patch(route('users.update', $data['customer']), [
                'role' => 'vendor',
            ])
            ->assertRedirect();

        $this->assertDatabaseHas('users', [
            'id' => $data['vendor']->id,
            'role' => 'vendor',
        ]);
    }

    public function test_admin_cannot_delete_self()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->delete(route('users.destroy', $admin))
            ->assertStatus(403);

        $this->assertDatabaseHas('users', [
            'id' => $admin->id,
        ]);
    }

}
