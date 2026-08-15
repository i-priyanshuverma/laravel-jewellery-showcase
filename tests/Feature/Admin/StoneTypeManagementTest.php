<?php

namespace Tests\Feature\Admin;

use App\Models\StoneType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoneTypeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_stone_types(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create
        $response = $this->actingAs($admin)->post(route('admin.stone-types.store'), [
            'name' => 'Blue Sapphire',
            'sort_order' => 1,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.stone-types.index'));
        $this->assertDatabaseHas('stone_types', ['name' => 'Blue Sapphire']);

        $stoneType = StoneType::where('name', 'Blue Sapphire')->first();

        // Update
        $response = $this->actingAs($admin)->put(route('admin.stone-types.update', $stoneType), [
            'name' => 'Ceylon Sapphire',
            'sort_order' => 2,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.stone-types.index'));
        $this->assertDatabaseHas('stone_types', ['name' => 'Ceylon Sapphire']);

        // Delete
        $response = $this->actingAs($admin)->delete(route('admin.stone-types.destroy', $stoneType));
        $response->assertRedirect(route('admin.stone-types.index'));
        $this->assertDatabaseMissing('stone_types', ['id' => $stoneType->id]);
    }
}
