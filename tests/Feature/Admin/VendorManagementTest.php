<?php

namespace Tests\Feature\Admin;

use App\Enums\UserStatus;
use App\Events\VendorStatusUpdated;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class VendorManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_vendors_list(): void
    {
        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->pending()->create();

        $response = $this->actingAs($admin)->get(route('admin.vendors.index'));

        $response->assertStatus(200);
        $response->assertSee($vendor->email);
    }

    public function test_admin_can_approve_pending_vendor_and_broadcast_event(): void
    {
        Event::fake([VendorStatusUpdated::class]);

        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->pending()->create();

        $response = $this->actingAs($admin)->patch(route('admin.vendors.approve', $vendor));

        $response->assertRedirect();
        $this->assertEquals(UserStatus::Approved, $vendor->fresh()->status);

        Event::assertDispatched(VendorStatusUpdated::class, function (VendorStatusUpdated $event) use ($vendor) {
            return $event->vendorId === $vendor->id && $event->status === 'approved' && $event->action === 'approved';
        });
    }

    public function test_admin_can_suspend_approved_vendor_and_broadcast_event(): void
    {
        Event::fake([VendorStatusUpdated::class]);

        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->approved()->create();

        $response = $this->actingAs($admin)->patch(route('admin.vendors.suspend', $vendor));

        $response->assertRedirect();
        $this->assertEquals(UserStatus::Suspended, $vendor->fresh()->status);

        Event::assertDispatched(VendorStatusUpdated::class, function (VendorStatusUpdated $event) use ($vendor) {
            return $event->vendorId === $vendor->id && $event->status === 'suspended' && $event->action === 'suspended';
        });
    }

    public function test_admin_can_reactivate_suspended_vendor_and_broadcast_event(): void
    {
        Event::fake([VendorStatusUpdated::class]);

        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->suspended()->create();

        $response = $this->actingAs($admin)->patch(route('admin.vendors.reactivate', $vendor));

        $response->assertRedirect();
        $this->assertEquals(UserStatus::Approved, $vendor->fresh()->status);

        Event::assertDispatched(VendorStatusUpdated::class, function (VendorStatusUpdated $event) use ($vendor) {
            return $event->vendorId === $vendor->id && $event->status === 'approved' && $event->action === 'reactivated';
        });
    }

    public function test_non_admin_cannot_access_vendor_management(): void
    {
        $vendor = User::factory()->vendor()->approved()->create();

        $response = $this->actingAs($vendor)->get(route('admin.vendors.index'));

        $response->assertStatus(403);
    }
}
