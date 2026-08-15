<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_profile_page_is_displayed(): void
    {
        $user = User::factory()->vendor()->approved()->create();

        $response = $this
            ->actingAs($user)
            ->get('/vendor/profile');

        $response->assertOk();
        $response->assertSee($user->email);
    }

    public function test_vendor_profile_information_can_be_updated(): void
    {
        $user = User::factory()->vendor()->approved()->create();

        $response = $this
            ->actingAs($user)
            ->put('/vendor/profile', [
                'name' => 'Updated Vendor Name',
                'business_name' => 'Updated Business Name',
                'email' => 'hacked@email.com',
                'phone' => '+91 9999999999',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect();

        $user->refresh();

        $this->assertSame('Updated Vendor Name', $user->name);
        $this->assertSame($user->email, $user->fresh()->email);
        $this->assertNotNull($user->vendorProfile);
        $this->assertSame('Updated Business Name', $user->vendorProfile->business_name);
    }
}
