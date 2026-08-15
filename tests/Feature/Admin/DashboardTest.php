<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_regular_vendor_cannot_access_admin_dashboard(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();

        $response = $this->actingAs($vendor)->get('/admin/dashboard');

        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_view_pending_vendors(): void
    {
        $admin = User::factory()->admin()->create();
        $pendingVendor = User::factory()->vendor()->pending()->create();

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOk();
        $response->assertViewIs('admin.dashboard');
        $response->assertViewHas('pendingVendorsList');
        $response->assertSeeText($pendingVendor->name);
    }

    public function test_admin_root_redirects_to_admin_dashboard(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin');

        $response->assertRedirect('/admin/dashboard');
    }
}
