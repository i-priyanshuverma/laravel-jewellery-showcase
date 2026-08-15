<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeRegistrationNotification extends Notification implements ShouldQueue
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
        $isVendor = $this->user->isVendor();

        $message = (new MailMessage)
            ->subject(__('Welcome to Sonar Haat — Jewellery Showcase'))
            ->greeting(__('Welcome, :name!', ['name' => $this->user->name]))
            ->line(__('Thank you for joining Sonar Haat, your premier certified jewellery and multi-vendor showcase platform.'));

        if ($isVendor) {
            $message->line(__('Your vendor account has been registered and is currently under administrative review.'))
                ->line(__('Our verification team will review your business credentials and activate your showcase dashboard shortly.'))
                ->action(__('Access Vendor Portal'), route('vendor.dashboard'));
        } else {
            $message->line(__('Explore certified hallmarked gold, silver, diamond, and precious jewellery from verified master craftsmen.'))
                ->action(__('Explore Catalogue'), route('products.index'));
        }

        return $message
            ->line(__('If you have any questions or need assistance, our support concierge is always ready to assist.'))
            ->salutation(__('Warm regards,')."\n".__('Sonar Haat Team'));
    }
}
