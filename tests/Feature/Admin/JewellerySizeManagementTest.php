<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\JewellerySize;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JewellerySizeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_sizes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $category = Category::factory()->create(['name' => 'Rings']);

        // Create
        $response = $this->actingAs($admin)->post(route('admin.sizes.store'), [
            'category_id' => $category->id,
            'name' => 'Ring Size 7',
            'value' => '7',
            'sort_order' => 1,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.sizes.index'));
        $this->assertDatabaseHas('jewellery_sizes', ['name' => 'Ring Size 7', 'value' => '7']);

        $size = JewellerySize::where('value', '7')->first();

        // Update
        $response = $this->actingAs($admin)->put(route('admin.sizes.update', $size), [
            'category_id' => $category->id,
            'name' => 'Size 7 (US)',
            'value' => '7',
            'sort_order' => 2,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.sizes.index'));
        $this->assertDatabaseHas('jewellery_sizes', ['name' => 'Size 7 (US)']);

        // Delete
        $response = $this->actingAs($admin)->delete(route('admin.sizes.destroy', $size));
        $response->assertRedirect(route('admin.sizes.index'));
        $this->assertDatabaseMissing('jewellery_sizes', ['id' => $size->id]);
    }
}
