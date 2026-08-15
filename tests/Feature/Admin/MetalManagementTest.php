<?php

namespace Tests\Feature\Admin;

use App\Models\Metal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MetalManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_metals_index(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $metal = Metal::create(['name' => 'Rose Gold', 'sort_order' => 1, 'status' => 'active']);
        $metal->purities()->create(['name' => '18K Rose', 'value' => '18K', 'sort_order' => 1, 'status' => 'active']);

        $response = $this->actingAs($admin)->get(route('admin.metals.index'));

        $response->assertStatus(200);
        $response->assertSee('Rose Gold');
        $response->assertSee('18K Rose');
    }

    public function test_admin_can_create_metal_with_purities(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.metals.store'), [
            'name' => 'Titanium',
            'sort_order' => 5,
            'status' => 'active',
            'purities' => [
                ['name' => 'Grade 5 Titanium', 'value' => 'Ti-6Al-4V', 'sort_order' => 1],
            ],
        ]);

        $response->assertRedirect(route('admin.metals.index'));
        $this->assertDatabaseHas('metals', ['name' => 'Titanium']);
        $this->assertDatabaseHas('purities', ['name' => 'Grade 5 Titanium', 'value' => 'Ti-6Al-4V']);
    }

    public function test_admin_can_update_metal_and_sync_purities(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $metal = Metal::create(['name' => 'Gold', 'sort_order' => 1, 'status' => 'active']);
        $purity = $metal->purities()->create(['name' => '22K (916)', 'value' => '22K', 'sort_order' => 1, 'status' => 'active']);

        $response = $this->actingAs($admin)->put(route('admin.metals.update', $metal), [
            'name' => 'Pure Gold',
            'sort_order' => 2,
            'status' => 'active',
            'purities' => [
                ['id' => $purity->id, 'name' => '22K Hallmark', 'value' => '22K', 'sort_order' => 1, 'status' => 'active'],
                ['id' => null, 'name' => '24K Fine', 'value' => '24K', 'sort_order' => 2, 'status' => 'active'],
            ],
        ]);

        $response->assertRedirect(route('admin.metals.index'));
        $this->assertDatabaseHas('metals', ['name' => 'Pure Gold']);
        $this->assertDatabaseHas('purities', ['name' => '22K Hallmark']);
        $this->assertDatabaseHas('purities', ['name' => '24K Fine']);
    }

    public function test_admin_can_delete_metal(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $metal = Metal::create(['name' => 'Bronze', 'sort_order' => 10, 'status' => 'active']);
        $metal->purities()->create(['name' => 'Standard', 'value' => 'Std', 'sort_order' => 1, 'status' => 'active']);

        $response = $this->actingAs($admin)->delete(route('admin.metals.destroy', $metal));

        $response->assertRedirect(route('admin.metals.index'));
        $this->assertDatabaseMissing('metals', ['id' => $metal->id]);
        $this->assertDatabaseMissing('purities', ['metal_id' => $metal->id]);
    }
}
