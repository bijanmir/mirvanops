<?php

namespace App\Console\Commands;

use App\Models\Lease;
use App\Models\Payment;
use App\Notifications\LeaseExpiringNotification;
use App\Notifications\PaymentReminderNotification;
use App\Notifications\PaymentOverdueNotification;
use Illuminate\Console\Command;

class SendLeaseReminders extends Command
{
    protected $signature = 'app:send-lease-reminders';
    protected $description = 'Send lease expiring and payment reminders';

    public function handle()
    {
        $this->sendLeaseExpiringReminders();
        $this->sendPaymentReminders();
        $this->sendOverdueReminders();
        
        $this->info('All reminders sent!');
    }

    protected function sendLeaseExpiringReminders()
    {
        $reminderDays = [30, 14, 7, 3, 1];

        foreach ($reminderDays as $days) {
            $targetDate = now()->addDays($days)->toDateString();

            $leases = Lease::where('status', 'active')
                ->whereDate('end_date', $targetDate)
                ->with(['tenant', 'unit.property', 'company.users'])
                ->get();

            foreach ($leases as $lease) {
                $users = $lease->company->users ?? collect();
                
                foreach ($users as $user) {
                    $user->notify(new LeaseExpiringNotification($lease, $days));
                }
            }

            $this->info("Sent {$leases->count()} lease expiring reminders for {$days} days.");
        }
    }

    protected function sendPaymentReminders()
    {
        if (now()->day !== 25) {
            return;
        }

        $currentPeriod = now()->addMonth()->format('F Y');

        $leases = Lease::where('status', 'active')
            ->with(['tenant', 'unit.property', 'company.users'])
            ->get();

        foreach ($leases as $lease) {
            $paymentExists = Payment::where('lease_id', $lease->id)
                ->where('period_covered', $currentPeriod)
                ->exists();

            if (!$paymentExists) {
                $users = $lease->company->users ?? collect();
                
                foreach ($users as $user) {
                    $user->notify(new PaymentReminderNotification($lease, $currentPeriod));
                }
            }
        }

        $this->info("Sent payment reminders for {$currentPeriod}.");
    }

    protected function sendOverdueReminders()
    {
        if (!in_array(now()->day, [5, 10, 15])) {
            return;
        }

        $currentPeriod = now()->format('F Y');
        $daysOverdue = now()->day;

        $leases = Lease::where('status', 'active')
            ->with(['tenant', 'unit.property', 'company.users'])
            ->get();

        foreach ($leases as $lease) {
            $paymentExists = Payment::where('lease_id', $lease->id)
                ->where('period_covered', $currentPeriod)
                ->where('status', 'completed')
                ->exists();

            if (!$paymentExists) {
                $users = $lease->company->users ?? collect();
                
                foreach ($users as $user) {
                    $user->notify(new PaymentOverdueNotification($lease, $currentPeriod, $daysOverdue));
                }
            }
        }

        $this->info("Sent overdue reminders for {$currentPeriod}.");
    }
}