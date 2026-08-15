<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\PasswordResetSuccessNotification;
use App\Notifications\ResetPasswordQueuedNotification;
use App\Notifications\WelcomeRegistrationNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class NotificationEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_welcome_notification_sent_on_registration(): void
    {
        Notification::fake();

        $this->post('/register', [
            'name' => 'Gold Artisan',
            'business_name' => 'Artisan Jewels',
            'email' => 'artisan@jewels.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $user = User::where('email', 'artisan@jewels.com')->first();
        $this->assertNotNull($user);

        Notification::assertSentTo($user, WelcomeRegistrationNotification::class, function (WelcomeRegistrationNotification $notification) use ($user) {
            $this->assertInstanceOf(ShouldQueue::class, $notification);
            $mail = $notification->toMail($user);
            $this->assertStringContainsString('Welcome', $mail->subject);

            return true;
        });
    }

    public function test_queued_password_reset_request_notification_sent(): void
    {
        Notification::fake();

        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordQueuedNotification::class, function (ResetPasswordQueuedNotification $notification) {
            $this->assertInstanceOf(ShouldQueue::class, $notification);

            return true;
        });
    }

    public function test_password_reset_success_notification_sent_on_reset(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $token = Password::createToken($user);

        $this->post('/reset-password', [
            'token' => $token,
            'email' => $user->email,
            'password' => 'new-secure-password',
            'password_confirmation' => 'new-secure-password',
        ]);

        Notification::assertSentTo($user, PasswordResetSuccessNotification::class, function (PasswordResetSuccessNotification $notification) use ($user) {
            $this->assertInstanceOf(ShouldQueue::class, $notification);
            $mail = $notification->toMail($user);
            $this->assertStringContainsString('Security Alert', $mail->subject);

            return true;
        });
    }

    public function test_notification_captures_active_locale_for_hindi(): void
    {
        Notification::fake();

        app()->setLocale('hi');
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordQueuedNotification::class, function (ResetPasswordQueuedNotification $notification) {
            return $notification->locale === 'hi';
        });
    }

    public function test_notification_captures_active_locale_for_arabic(): void
    {
        Notification::fake();

        app()->setLocale('ar');
        $user = User::factory()->create();

        $this->post('/forgot-password', ['email' => $user->email]);

        Notification::assertSentTo($user, ResetPasswordQueuedNotification::class, function (ResetPasswordQueuedNotification $notification) {
            return $notification->locale === 'ar';
        });
    }
}
