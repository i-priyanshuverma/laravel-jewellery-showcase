<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(public User $user)
    {
        $this->locale = app()->getLocale();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Sonar Haat — Security Alert: Password Changed Successfully'))
            ->greeting(__('Hello :name,', ['name' => $this->user->name]))
            ->line(__('This is a security confirmation that the password for your Sonar Haat account (:email) was successfully updated.', ['email' => $this->user->email]))
            ->line(__('You can now log in securely with your new password.'))
            ->action(__('Sign In to Your Account'), route('login'))
            ->line(__('If you did NOT make this change, please contact our security team immediately to protect your account.'))
            ->salutation(__('Warm regards,')."\n".__('Sonar Haat Security Team'));
    }
}
