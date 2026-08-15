<?php

namespace Tests\Feature\Auth;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_new_vendor_can_register_and_receives_pending_status(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'business_name' => 'Doe Fine Jewellery',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();

        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals(UserRole::Vendor, $user->role);
        $this->assertEquals(UserStatus::Pending, $user->status);
        $this->assertNotNull($user->vendorProfile);
        $this->assertEquals('Doe Fine Jewellery', $user->vendorProfile->business_name);

        $response->assertRedirect(route('vendor.dashboard'));
    }
}
