<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Mirvan Properties!')
            ->greeting('Welcome, ' . $notifiable->name . '!')
            ->line('Thank you for signing up for Mirvan Properties. We\'re excited to help you manage your properties more efficiently.')
            ->line('Here\'s what you can do next:')
            ->line('• Add your first property')
            ->line('• Set up units and tenants')
            ->line('• Track leases and payments')
            ->action('Go to Dashboard', url('/dashboard'))
            ->line('If you have any questions, just reply to this email.')
            ->salutation('Welcome aboard!');
    }
}