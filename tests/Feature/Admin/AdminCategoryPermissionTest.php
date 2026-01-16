<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminCategoryPermissionTest extends TestCase
{
    use RefreshDatabase;

    private function createCategories()
    {
        $categoryA = Category::factory()->create();

        $categoryB = Category::factory()->create();


        return compact(
            'categoryA',
            'categoryB'
        );
    }
    public function test_admin_can_see_all_categories()
    {
        $data = $this->createCategories();

        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('categories.index'))
            ->assertInertia(
                fn ($page) =>
                $page->where(
                    'categories.data',
                    fn ($categories) =>
                    collect($categories)->pluck('id')->contains($data['categoryA']->id)
                    && collect($categories)->pluck('id')->contains($data['categoryB']->id)
                )
            );
    }

    public function test_admin_can_create_category()
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->actingAs($user)
             ->post(route('categories.store'), [
                 'name' => 'Test Category'
             ])
             ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category'
        ]);
    }

    public function test_admin_can_update_any_category()
    {

        $user = User::factory()->create(['role' => 'admin']);

        $category = Category::factory()->create();

        $this->actingAs($user)
             ->put(route('categories.update', $category), [
                 'name' => 'Updated Category'
             ])
             ->assertRedirect(route('categories.index'));

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Category'
        ]);
    }

    public function test_admin_can_delete_any_category()
    {

        $user = User::factory()->create(['role' => 'admin']);

        $category = Category::factory()->create();

        $this->actingAs($user)
            ->delete(route('categories.destroy', $category->id))
            ->assertRedirect(route('categories.index'));

        $this->assertSoftDeleted('categories', [
            'id' => $category->id,
        ]);

    }
}
