<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class SecurityAndLocaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_security_headers_are_attached_to_all_responses(): void
    {
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $this->assertNotNull($response->headers->get('Content-Security-Policy'));
    }

    public function test_user_can_switch_locale_to_hindi(): void
    {
        $response = $this->get(route('locale.switch', ['locale' => 'hi']));

        $response->assertRedirect();
        $response->assertCookie('locale', 'hi');
    }

    public function test_user_can_switch_locale_to_arabic_and_renders_rtl(): void
    {
        $this->withSession(['locale' => 'ar']);
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertSee('dir="rtl"', false);
    }

    public function test_invalid_locale_falls_back_gracefully(): void
    {
        $this->get(route('locale.switch', ['locale' => 'invalid_locale']));
        $this->assertEquals(config('app.fallback_locale', 'en'), app()->getLocale());
    }

    public function test_telescope_gate_authorizes_admin_and_denies_vendor(): void
    {
        $admin = User::factory()->admin()->create();
        $vendor = User::factory()->vendor()->create();

        $this->assertTrue(Gate::forUser($admin)->allows('viewTelescope'));
        $this->assertFalse(Gate::forUser($vendor)->allows('viewTelescope'));
    }
}
