<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Notifications\PaymentFailedNotification;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Webhook;
use Carbon\Carbon;

class WebhookController extends Controller
{
    public function handleStripe(Request $request)
    {
        Stripe::setApiKey(config('stripe.secret'));

        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = config('stripe.webhook_secret');

        if ($webhookSecret) {
            try {
                $event = Webhook::constructEvent($payload, $signature, $webhookSecret);
            } catch (\Exception $e) {
                return response('Invalid signature', 400);
            }
        } else {
            $event = json_decode($payload);
        }

        switch ($event->type) {
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdate($event->data->object);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionDeleted($event->data->object);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;

            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
        }

        return response('OK', 200);
    }

    protected function handleSubscriptionUpdate($subscription)
    {
        $company = Company::where('stripe_id', $subscription->customer)->first();

        if ($company) {
            $priceId = $subscription->items->data[0]->price->id ?? null;
            $plan = $this->getPlanFromPriceId($priceId);

            $status = $subscription->status;
            if ($subscription->cancel_at_period_end) {
                $status = 'canceling';
            }

            $company->update([
                'subscription_id' => $subscription->id,
                'plan' => $plan,
                'subscription_status' => $status,
                'subscription_ends_at' => $subscription->current_period_end 
                    ? now()->setTimestamp($subscription->current_period_end) 
                    : null,
            ]);
        }
    }

    protected function handleSubscriptionDeleted($subscription)
    {
        $company = Company::where('stripe_id', $subscription->customer)->first();

        if ($company) {
            $company->update([
                'plan' => 'free',
                'subscription_status' => 'cancelled',
                'subscription_id' => null,
                'subscription_ends_at' => null,
                'grace_period_ends_at' => null,
            ]);
        }
    }

    protected function handlePaymentFailed($invoice)
    {
        $company = Company::where('stripe_id', $invoice->customer)->first();

        if ($company) {
            // Set 7-day grace period from now
            $gracePeriodEnd = now()->addDays(7);
            
            $company->update([
                'subscription_status' => 'past_due',
                'grace_period_ends_at' => $gracePeriodEnd,
            ]);

            // Send email notification to all company users
            $this->notifyPaymentFailed($company, $gracePeriodEnd);
        }
    }

    protected function handlePaymentSucceeded($invoice)
    {
        $company = Company::where('stripe_id', $invoice->customer)->first();

        if ($company && $company->subscription_status === 'past_due') {
            // Payment recovered - clear grace period
            $company->update([
                'subscription_status' => 'active',
                'grace_period_ends_at' => null,
            ]);
        }
    }

    protected function notifyPaymentFailed(Company $company, Carbon $gracePeriodEnd)
    {
        // Notify all users in the company
        foreach ($company->users as $user) {
            try {
                $user->notify(new PaymentFailedNotification($gracePeriodEnd));
            } catch (\Exception $e) {
                // Log but don't fail
                \Log::error('Failed to send payment failed notification: ' . $e->getMessage());
            }
        }
    }

    protected function getPlanFromPriceId(?string $priceId): string
    {
        if (!$priceId) return 'free';

        $prices = config('stripe.prices');

        foreach ($prices as $plan => $id) {
            if ($id === $priceId) {
                return $plan;
            }
        }

        return 'free';
    }
}
