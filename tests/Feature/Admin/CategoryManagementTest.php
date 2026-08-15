<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_manage_categories(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Bangles', 'slug' => 'bangles']);

        // Index
        $response = $this->actingAs($admin)->get(route('admin.categories.index'));
        $response->assertStatus(200);
        $response->assertSee('Bangles');

        // Create
        $createResponse = $this->actingAs($admin)->post(route('admin.categories.store'), [
            'name' => 'Anklets',
        ]);
        $createResponse->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Anklets']);

        // Edit view
        $editResponse = $this->actingAs($admin)->get(route('admin.categories.edit', $category));
        $editResponse->assertStatus(200);

        // Update
        $updateResponse = $this->actingAs($admin)->put(route('admin.categories.update', $category), [
            'name' => 'Royal Bangles',
        ]);
        $updateResponse->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseHas('categories', ['name' => 'Royal Bangles']);

        // Delete
        $deleteResponse = $this->actingAs($admin)->delete(route('admin.categories.destroy', $category));
        $deleteResponse->assertRedirect(route('admin.categories.index'));
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
