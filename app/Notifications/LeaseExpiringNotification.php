<?php

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LeaseExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lease $lease,
        public int $daysRemaining
    ) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $tenant = $this->lease->tenant->full_name ?? 'Unknown Tenant';
        $unit = $this->lease->unit->unit_number ?? 'Unknown';
        $property = $this->lease->unit->property->name ?? 'Unknown Property';
        $endDate = $this->lease->end_date->format('M d, Y');

        $subject = $this->daysRemaining <= 7 
            ? "⚠️ Lease expiring in {$this->daysRemaining} days - {$tenant}"
            : "Lease expiring in {$this->daysRemaining} days - {$tenant}";

        return (new MailMessage)
            ->subject($subject)
            ->greeting('Lease Expiration Reminder')
            ->line("The following lease is expiring soon:")
            ->line("**Tenant:** {$tenant}")
            ->line("**Property:** {$property} - Unit {$unit}")
            ->line("**Expires:** {$endDate} ({$this->daysRemaining} days)")
            ->action('View Lease', url("/leases/{$this->lease->id}/edit"))
            ->line('Consider reaching out to the tenant about renewal.');
    }
}