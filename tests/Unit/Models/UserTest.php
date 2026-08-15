<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\VendorProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_roles_and_status_helpers(): void
    {
        $admin = User::factory()->admin()->create();
        $approvedVendor = User::factory()->vendor()->approved()->create();
        $pendingVendor = User::factory()->vendor()->pending()->create();
        $suspendedVendor = User::factory()->vendor()->suspended()->create();

        $this->assertTrue($admin->isAdmin());
        $this->assertFalse($admin->isVendor());

        $this->assertTrue($approvedVendor->isVendor());
        $this->assertTrue($approvedVendor->isApproved());
        $this->assertFalse($approvedVendor->isAdmin());

        $this->assertTrue($pendingVendor->isPending());
        $this->assertFalse($pendingVendor->isApproved());

        $this->assertTrue($suspendedVendor->isSuspended());
        $this->assertFalse($suspendedVendor->isApproved());
    }

    public function test_user_has_one_vendor_profile(): void
    {
        $vendor = User::factory()->vendor()->create();
        $profile = VendorProfile::factory()->create(['user_id' => $vendor->id]);

        $this->assertNotNull($vendor->vendorProfile);
        $this->assertEquals($profile->id, $vendor->vendorProfile->id);
    }
}
