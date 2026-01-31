<x-app-layout>
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">Billing & Plans</h1>
            <p class="text-muted mt-1">Manage your subscription</p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-500">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500">
            {{ session('error') }}
        </div>
        @endif

        <!-- Current Plan -->
        <div class="glass-card rounded-xl p-6 mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-lg font-semibold text-primary">Current Plan</h2>
                    <p class="text-2xl font-bold text-accent mt-1">{{ $plans[$currentPlan]['name'] }}</p>
                    <p class="text-muted text-sm mt-1">
                        Using {{ $unitCount }} of {{ $unitLimit }} units
                    </p>
                    
                    @if($company->subscription_status === 'canceling')
                    <p class="text-amber-500 text-sm mt-2">
                        ⚠️ Your subscription will end on {{ $company->subscription_ends_at?->format('M d, Y') ?? 'the end of your billing period' }}
                    </p>
                    @endif
                    
                    <!-- Usage Bar -->
                    <div class="mt-3 w-64">
                        <div class="h-2 bg-input rounded-full overflow-hidden">
                            @php $percentage = $unitLimit > 0 ? min(100, ($unitCount / $unitLimit) * 100) : 0; @endphp
                            <div class="h-full rounded-full {{ $percentage > 90 ? 'bg-red-500' : ($percentage > 70 ? 'bg-amber-500' : 'bg-green-500') }}" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3">
                    @if($company->onPaidPlan())
                        <a href="{{ route('billing.portal') }}" class="btn-secondary px-6 py-3 rounded-xl font-medium inline-flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Manage Billing
                        </a>
                        
                        @if($company->subscription_status === 'canceling')
                            <form action="{{ route('billing.resume') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="btn-primary px-6 py-3 rounded-xl font-medium inline-flex items-center justify-center w-full">
                                    Resume Subscription
                                </button>
                            </form>
                        @else
                            <form action="{{ route('billing.cancel') }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to cancel? You will retain access until the end of your billing period.')">
                                @csrf
                                <button type="submit" class="px-6 py-3 rounded-xl font-medium text-red-500 hover:bg-red-500/10 transition-colors">
                                    Cancel Subscription
                                </button>
                            </form>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- Plans Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($plans as $planKey => $plan)
            <div class="glass-card rounded-xl p-6 {{ $currentPlan === $planKey ? 'ring-2 ring-accent' : '' }}">
                @if($currentPlan === $planKey)
                <span class="inline-block px-3 py-1 text-xs font-semibold bg-accent text-white rounded-full mb-4">Current Plan</span>
                @endif
                
                <h3 class="text-xl font-bold text-primary">{{ $plan['name'] }}</h3>
                
                <div class="mt-4">
                    <span class="text-4xl font-bold text-primary">${{ $plan['price'] }}</span>
                    @if($plan['price'] > 0)
                    <span class="text-muted">/month</span>
                    @endif
                </div>
                
                <p class="text-muted text-sm mt-2">Up to {{ $plan['unit_limit'] }} units</p>
                
                <ul class="mt-6 space-y-3">
                    @foreach($plan['features'] as $feature)
                    <li class="flex items-start text-sm text-secondary">
                        <svg class="w-5 h-5 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        {{ $feature }}
                    </li>
                    @endforeach
                </ul>
                
                <div class="mt-6">
                    @if($currentPlan === $planKey)
                        <button disabled class="w-full py-3 rounded-xl font-medium bg-input text-muted cursor-not-allowed">
                            Current Plan
                        </button>
                    @elseif($planKey === 'free')
                        {{-- No button for free plan - use cancel instead --}}
                    @else
                        <a href="{{ route('billing.checkout', $planKey) }}" class="block w-full py-3 rounded-xl font-medium text-center btn-primary">
                            @if($company->onFreePlan())
                                Upgrade
                            @elseif($plan['price'] > $plans[$currentPlan]['price'])
                                Upgrade
                            @else
                                Downgrade
                            @endif
                        </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        <!-- FAQ -->
        <div class="mt-12 glass-card rounded-xl p-6">
            <h2 class="text-lg font-semibold text-primary mb-4">Frequently Asked Questions</h2>
            
            <div class="space-y-4">
                <div>
                    <h3 class="font-medium text-primary">Can I change plans anytime?</h3>
                    <p class="text-muted text-sm mt-1">Yes, you can upgrade or downgrade at any time. When upgrading, you'll be charged a prorated amount. When downgrading, you'll receive credit toward future invoices.</p>
                </div>
                <div>
                    <h3 class="font-medium text-primary">What happens if I exceed my unit limit?</h3>
                    <p class="text-muted text-sm mt-1">You won't be able to add new units until you upgrade or remove existing units.</p>
                </div>
                <div>
                    <h3 class="font-medium text-primary">What happens when I cancel?</h3>
                    <p class="text-muted text-sm mt-1">You'll retain full access until the end of your current billing period. After that, your account will revert to the Free plan.</p>
                </div>
                <div>
                    <h3 class="font-medium text-primary">Can I get a refund?</h3>
                    <p class="text-muted text-sm mt-1">We don't offer refunds, but you can cancel anytime and keep access until your billing period ends.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
