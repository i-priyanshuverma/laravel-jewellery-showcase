<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordQueuedNotification extends ResetPassword implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(string $token)
    {
        parent::__construct($token);
        $this->locale = app()->getLocale();
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param  string  $url
     */
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject(__('Sonar Haat — Password Reset Request'))
            ->greeting(__('Hello,'))
            ->line(__('You are receiving this email because we received a password reset request for your Sonar Haat account.'))
            ->action(__('Reset My Password'), $url)
            ->line(__('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(__('If you did not request a password reset, no further action is required and your account remains completely secure.'))
            ->salutation(__('Warm regards,')."\n".__('Sonar Haat Team'));
    }
}
