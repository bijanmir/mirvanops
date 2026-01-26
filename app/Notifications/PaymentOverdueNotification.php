<?php

namespace App\Notifications;

use App\Models\Lease;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentOverdueNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Lease $lease,
        public string $period,
        public int $daysOverdue
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
        $rent = number_format($this->lease->rent_amount);

        return (new MailMessage)
            ->subject("⚠️ Payment overdue - {$tenant} - {$this->daysOverdue} days")
            ->greeting('Payment Overdue')
            ->line("The following payment is overdue:")
            ->line("**Tenant:** {$tenant}")
            ->line("**Property:** {$property} - Unit {$unit}")
            ->line("**Amount Due:** \${$rent}")
            ->line("**Period:** {$this->period}")
            ->line("**Days Overdue:** {$this->daysOverdue}")
            ->action('Record Payment', url("/payments/create?lease_id={$this->lease->id}"))
            ->line('Consider following up with the tenant.');
    }
}