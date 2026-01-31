<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class PaymentFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Carbon $gracePeriodEnd;

    public function __construct(Carbon $gracePeriodEnd)
    {
        $this->gracePeriodEnd = $gracePeriodEnd;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $daysRemaining = now()->diffInDays($this->gracePeriodEnd);

        return (new MailMessage)
            ->subject('⚠️ Payment Failed - Action Required')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We were unable to process your subscription payment for Mirvan Properties.')
            ->line("You have **{$daysRemaining} days** to update your payment method before your account is downgraded to the Free plan.")
            ->line('Your current features and data will remain accessible during this grace period.')
            ->action('Update Payment Method', route('billing.portal'))
            ->line('If you have any questions, please reply to this email.')
            ->salutation('Thanks, The Mirvan Properties Team');
    }
}
