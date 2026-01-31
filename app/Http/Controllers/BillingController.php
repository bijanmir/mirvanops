<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Customer;
use Stripe\Subscription;

class BillingController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('stripe.secret'));
    }

    public function index()
    {
        $company = auth()->user()->company;
        $plans = config('stripe.plans');
        $currentPlan = $company->plan ?? 'free';
        $unitCount = $company->units()->count();
        $unitLimit = $company->getUnitLimit();

        return view('billing.index', compact('company', 'plans', 'currentPlan', 'unitCount', 'unitLimit'));
    }

    public function checkout(Request $request, string $plan)
    {
        $company = auth()->user()->company;
        $priceId = config("stripe.prices.{$plan}");

        if (!$priceId) {
            return back()->with('error', 'Invalid plan selected.');
        }

        // Create or get Stripe customer
        if (!$company->stripe_id) {
            $customer = Customer::create([
                'email' => auth()->user()->email,
                'name' => $company->name,
                'metadata' => [
                    'company_id' => $company->id,
                ],
            ]);
            $company->update(['stripe_id' => $customer->id]);
        }

        // If already has a subscription, update it instead of creating new one
        if ($company->subscription_id) {
            try {
                $subscription = Subscription::retrieve($company->subscription_id);
                
                // Update the subscription to the new price
                Subscription::update($company->subscription_id, [
                    'items' => [
                        [
                            'id' => $subscription->items->data[0]->id,
                            'price' => $priceId,
                        ],
                    ],
                    'proration_behavior' => 'create_prorations',
                ]);

                $company->update(['plan' => $plan]);

                return redirect()->route('billing.index')->with('success', 'Subscription updated successfully!');
            } catch (\Exception $e) {
                // If subscription update fails, create new checkout session
            }
        }

        // Create new checkout session for new subscribers
        $session = Session::create([
            'customer' => $company->stripe_id,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('billing.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.index'),
            'metadata' => [
                'company_id' => $company->id,
                'plan' => $plan,
            ],
        ]);

        return redirect($session->url);
    }

    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');

        if ($sessionId) {
            $session = Session::retrieve($sessionId);
            $company = auth()->user()->company;

            $company->update([
                'subscription_id' => $session->subscription,
                'plan' => $session->metadata->plan ?? 'starter',
                'subscription_status' => 'active',
            ]);
        }

        return redirect()->route('billing.index')->with('success', 'Subscription activated successfully!');
    }

    public function portal()
    {
        $company = auth()->user()->company;

        if (!$company->stripe_id) {
            return back()->with('error', 'No billing account found.');
        }

        $session = PortalSession::create([
            'customer' => $company->stripe_id,
            'return_url' => route('billing.index'),
        ]);

        return redirect($session->url);
    }

    public function cancel()
    {
        $company = auth()->user()->company;

        if (!$company->subscription_id) {
            return back()->with('error', 'No active subscription found.');
        }

        try {
            // Cancel at period end - user keeps access until subscription ends
            Subscription::update($company->subscription_id, [
                'cancel_at_period_end' => true,
            ]);

            $company->update([
                'subscription_status' => 'canceling',
            ]);

            return back()->with('success', 'Your subscription will be canceled at the end of the billing period. You will retain access until then.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel subscription. Please try again.');
        }
    }

    public function resume()
    {
        $company = auth()->user()->company;

        if (!$company->subscription_id) {
            return back()->with('error', 'No subscription found.');
        }

        try {
            // Resume subscription (undo cancel at period end)
            Subscription::update($company->subscription_id, [
                'cancel_at_period_end' => false,
            ]);

            $company->update([
                'subscription_status' => 'active',
            ]);

            return back()->with('success', 'Your subscription has been resumed.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to resume subscription. Please try again.');
        }
    }
}
