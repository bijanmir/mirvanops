@php
    $company = auth()->user()->company;
    $isOverLimit = $company->isOverLimit();
    $unitCount = $company->units()->count();
    $unitLimit = $company->getUnitLimit();
    $planName = $company->getPlanConfig()['name'];
    $isInGracePeriod = $company->isInGracePeriod();
    $gracePeriodDays = $company->gracePeriodDaysRemaining();
@endphp

@if($company->subscription_status === 'past_due' && $isInGracePeriod)
<div class="mb-6 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-amber-500 font-medium">Payment Failed - Action Required</p>
                <p class="text-amber-400 text-sm mt-1">
                    Your last payment failed. You have <strong>{{ $gracePeriodDays }} days</strong> to update your payment method before your account is downgraded to the Free plan.
                </p>
            </div>
        </div>
        <a href="{{ route('billing.portal') }}" class="btn-primary px-4 py-2 rounded-lg text-sm whitespace-nowrap flex-shrink-0">
            Update Payment
        </a>
    </div>
</div>
@endif

@if($isOverLimit)
<div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <div>
                <p class="text-red-500 font-medium">You've exceeded your plan limit</p>
                <p class="text-red-400 text-sm mt-1">
                    You have {{ $unitCount }} units but your {{ $planName }} plan allows {{ $unitLimit }}. 
                    You can still manage existing data, but you cannot add new units, properties, or leases until you upgrade.
                </p>
            </div>
        </div>
        <a href="{{ route('billing.index') }}" class="btn-primary px-4 py-2 rounded-lg text-sm whitespace-nowrap flex-shrink-0">
            Upgrade Now
        </a>
    </div>
</div>
@endif
