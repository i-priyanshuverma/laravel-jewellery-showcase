<?php

namespace Tests\Feature\Admin;

use App\Models\Colour;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ColourManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_manage_colours(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        // Create
        $response = $this->actingAs($admin)->post(route('admin.colours.store'), [
            'name' => 'Rose',
            'sort_order' => 1,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.colours.index'));
        $this->assertDatabaseHas('colours', ['name' => 'Rose']);

        $colour = Colour::where('name', 'Rose')->first();

        // Update
        $response = $this->actingAs($admin)->put(route('admin.colours.update', $colour), [
            'name' => 'Rose Gold Finish',
            'sort_order' => 2,
            'status' => 'active',
        ]);
        $response->assertRedirect(route('admin.colours.index'));
        $this->assertDatabaseHas('colours', ['name' => 'Rose Gold Finish']);

        // Delete
        $response = $this->actingAs($admin)->delete(route('admin.colours.destroy', $colour));
        $response->assertRedirect(route('admin.colours.index'));
        $this->assertDatabaseMissing('colours', ['id' => $colour->id]);
    }
}
