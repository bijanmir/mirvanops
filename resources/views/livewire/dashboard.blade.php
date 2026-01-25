<div>
    <!-- Welcome Header -->
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-primary">Good {{ now()->format('H') < 12 ? 'morning' : (now()->format('H') < 17 ? 'afternoon' : 'evening') }}, {{ explode(' ', auth()->user()->name)[0] }}!</h1>
        <p class="text-muted mt-1">Here's what's happening with your properties today.</p>
    </div>

    <!-- Financial Overview -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-green-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-500 bg-green-500/10 px-2 py-1 rounded-full">This Month</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($financial['collected']) }}</p>
            <p class="text-sm text-muted">Collected</p>
        </div>

        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-amber-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($financial['outstanding']) }}</p>
            <p class="text-sm text-muted">Outstanding</p>
        </div>

        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-accent/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $financial['collection_rate'] }}%</p>
            <p class="text-sm text-muted">Collection Rate</p>
        </div>

        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-3">
                <div class="w-10 h-10 rounded-lg bg-blue-500/15 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $stats['occupancy_rate'] }}%</p>
            <p class="text-sm text-muted">Occupancy ({{ $stats['vacant_units'] }} vacant)</p>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <a href="{{ route('properties.index') }}" class="glass-card rounded-xl p-4 hover:border-accent/50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-indigo-500/15 flex items-center justify-center group-hover:bg-indigo-500/25 transition-colors">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-primary">{{ $stats['properties'] }}</p>
                    <p class="text-xs text-muted">Properties</p>
                </div>
            </div>
        </a>

        <a href="{{ route('tenants.index') }}" class="glass-card rounded-xl p-4 hover:border-accent/50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-500/15 flex items-center justify-center group-hover:bg-purple-500/25 transition-colors">
                    <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-primary">{{ $stats['tenants'] }}</p>
                    <p class="text-xs text-muted">Tenants</p>
                </div>
            </div>
        </a>

        <a href="{{ route('leases.index') }}" class="glass-card rounded-xl p-4 hover:border-accent/50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-cyan-500/15 flex items-center justify-center group-hover:bg-cyan-500/25 transition-colors">
                    <svg class="w-5 h-5 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-primary">{{ $stats['active_leases'] }}</p>
                    <p class="text-xs text-muted">Active Leases</p>
                </div>
            </div>
        </a>

        <a href="{{ route('maintenance.index') }}" class="glass-card rounded-xl p-4 hover:border-accent/50 transition-colors group">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-rose-500/15 flex items-center justify-center group-hover:bg-rose-500/25 transition-colors">
                    <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
                <div>
                    <p class="text-xl font-bold text-primary">{{ $openMaintenance }}</p>
                    <p class="text-xs text-muted">Open Requests</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Payments Due -->
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="p-4 border-b border-primary flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-primary">Payments Due - {{ $currentMonth }}</h2>
                    </div>
                    @if($paymentsDue->count() > 0)
                    <span class="text-xs font-medium text-amber-500 bg-amber-500/10 px-2 py-1 rounded-full">{{ $paymentsDue->count() }} pending</span>
                    @endif
                </div>
                
                @if($paymentsDue->count() > 0)
                <div class="divide-y divide-primary">
                    @foreach($paymentsDue->take(5) as $lease)
                    <div class="p-4 flex items-center justify-between hover:bg-input/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                <span class="text-sm font-bold text-white">{{ substr($lease->tenant->first_name ?? 'T', 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-primary">{{ $lease->tenant->full_name ?? 'Unknown' }}</p>
                                <p class="text-xs text-muted">{{ $lease->unit->property->name ?? '' }} - Unit {{ $lease->unit->unit_number ?? '' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-sm font-bold text-primary">${{ number_format($lease->rent_amount + ($lease->pet_rent ?? 0)) }}</p>
                                <p class="text-xs text-muted">Due {{ $lease->payment_due_day }}{{ $lease->payment_due_day == 1 ? 'st' : ($lease->payment_due_day == 2 ? 'nd' : ($lease->payment_due_day == 3 ? 'rd' : 'th')) }}</p>
                            </div>
                            <a href="{{ route('payments.create', ['lease_id' => $lease->id]) }}" class="btn-primary px-3 py-1.5 rounded-lg text-xs font-medium">
                                Record
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @if($paymentsDue->count() > 5)
                <div class="p-3 border-t border-primary bg-input/30">
                    <a href="{{ route('payments.index') }}" class="text-sm text-accent hover:underline">View all {{ $paymentsDue->count() }} pending →</a>
                </div>
                @endif
                @else
                <div class="p-8 text-center">
                    <div class="w-12 h-12 rounded-full bg-green-500/15 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm text-muted">All payments collected for {{ $currentMonth }}! 🎉</p>
                </div>
                @endif
            </div>

            <!-- Recent Payments -->
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="p-4 border-b border-primary flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-primary">Recent Payments</h2>
                    </div>
                    <a href="{{ route('payments.index') }}" class="text-sm text-accent hover:underline">View all →</a>
                </div>
                
                @if($recentPayments->count() > 0)
                <div class="divide-y divide-primary">
                    @foreach($recentPayments as $payment)
                    <div class="p-4 flex items-center justify-between hover:bg-input/50 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-green-500/15 flex items-center justify-center flex-shrink-0">
                                @if($payment->payment_method === 'ach')
                                    <span class="text-lg">🏦</span>
                                @elseif($payment->payment_method === 'check')
                                    <span class="text-lg">📝</span>
                                @elseif($payment->payment_method === 'cash')
                                    <span class="text-lg">💵</span>
                                @elseif($payment->payment_method === 'zelle')
                                    <span class="text-lg">⚡</span>
                                @elseif($payment->payment_method === 'venmo')
                                    <span class="text-lg">📱</span>
                                @else
                                    <span class="text-lg">💳</span>
                                @endif
                            </div>
                            <div>
                                <p class="text-sm font-medium text-primary">{{ $payment->tenant->full_name ?? 'Unknown' }}</p>
                                <p class="text-xs text-muted">{{ $payment->period_covered }} • {{ $payment->payment_method_label }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-green-500">+${{ number_format($payment->amount) }}</p>
                            <p class="text-xs text-muted">{{ $payment->payment_date->diffForHumans() }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center">
                    <p class="text-sm text-muted">No payments recorded yet.</p>
                    <a href="{{ route('payments.create') }}" class="text-sm text-accent hover:underline mt-2 inline-block">Record first payment →</a>
                </div>
                @endif
            </div>

            <!-- Maintenance Alerts -->
            @if($maintenanceAlerts->count() > 0)
            <div class="glass-card rounded-xl overflow-hidden border-l-4 border-red-500">
                <div class="p-4 border-b border-primary flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-primary">Maintenance Alerts</h2>
                    </div>
                    <a href="{{ route('maintenance.index') }}" class="text-sm text-accent hover:underline">View all →</a>
                </div>
                
                <div class="divide-y divide-primary">
                    @foreach($maintenanceAlerts as $request)
                    <a href="{{ route('maintenance.show', $request) }}" class="p-4 flex items-center justify-between hover:bg-input/50 transition-colors block">
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                                @if($request->priority === 'emergency') bg-red-500/15 text-red-500
                                @else bg-orange-500/15 text-orange-500
                                @endif">
                                @if($request->priority === 'emergency') 🔴 @else 🟠 @endif
                                {{ ucfirst($request->priority) }}
                            </span>
                            <div>
                                <p class="text-sm font-medium text-primary">{{ $request->title }}</p>
                                <p class="text-xs text-muted">{{ $request->property->name ?? '' }}@if($request->unit) - Unit {{ $request->unit->unit_number }}@endif</p>
                            </div>
                        </div>
                        <span class="text-xs text-muted">{{ $request->created_at->diffForHumans() }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column (1/3) -->
        <div class="space-y-6">
            <!-- Leases Expiring Soon -->
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="p-4 border-b border-primary">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-primary">Expiring Soon</h2>
                    </div>
                    <p class="text-xs text-muted mt-1">Leases ending in 60 days</p>
                </div>
                
                @if($expiringLeases->count() > 0)
                <div class="divide-y divide-primary">
                    @foreach($expiringLeases as $lease)
                    @php
                        $daysLeft = now()->diffInDays($lease->end_date, false);
                    @endphp
                    <a href="{{ route('leases.edit', $lease) }}" class="p-4 block hover:bg-input/50 transition-colors">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-primary">{{ $lease->tenant->full_name ?? 'Unknown' }}</p>
                            <span class="text-xs font-medium px-2 py-0.5 rounded-full
                                @if($daysLeft <= 14) bg-red-500/15 text-red-500
                                @elseif($daysLeft <= 30) bg-amber-500/15 text-amber-500
                                @else bg-blue-500/15 text-blue-500
                                @endif">
                                {{ $daysLeft }} days
                            </span>
                        </div>
                        <p class="text-xs text-muted">{{ $lease->unit->property->name ?? '' }} - Unit {{ $lease->unit->unit_number ?? '' }}</p>
                        <p class="text-xs text-muted mt-1">Expires {{ $lease->end_date->format('M d, Y') }}</p>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="p-6 text-center">
                    <div class="w-10 h-10 rounded-full bg-green-500/15 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <p class="text-sm text-muted">No leases expiring soon</p>
                </div>
                @endif
            </div>

            <!-- Recent Activity -->
            <div class="glass-card rounded-xl overflow-hidden">
                <div class="p-4 border-b border-primary">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-lg font-semibold text-primary">Recent Activity</h2>
                    </div>
                </div>
                
                @if($recentActivity->count() > 0)
                <div class="divide-y divide-primary">
                    @foreach($recentActivity as $log)
                    <div class="p-3 hover:bg-input/50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-accent/15 flex items-center justify-center flex-shrink-0 mt-0.5">
                                @if($log->action === 'created')
                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                @elseif($log->action === 'updated')
                                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                @else
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-primary">
                                    <span class="font-medium">{{ ucfirst($log->action) }}</span>
                                    {{ strtolower(class_basename($log->model_type)) }}
                                </p>
                                <p class="text-xs text-muted">{{ $log->user->name ?? 'System' }} • {{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-6 text-center">
                    <p class="text-sm text-muted">No recent activity</p>
                </div>
                @endif
            </div>

            <!-- Quick Actions -->
            <div class="glass-card rounded-xl p-4">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('payments.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-green-500/10 hover:bg-green-500/20 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center group-hover:bg-green-500/30">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-green-500">Record Payment</span>
                    </a>
                    <a href="{{ route('maintenance.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-amber-500/10 hover:bg-amber-500/20 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/20 flex items-center justify-center group-hover:bg-amber-500/30">
                            <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-amber-500">New Maintenance</span>
                    </a>
                    <a href="{{ route('tenants.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-purple-500/10 hover:bg-purple-500/20 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center group-hover:bg-purple-500/30">
                            <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-purple-500">Add Tenant</span>
                    </a>
                    <a href="{{ route('leases.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-cyan-500/10 hover:bg-cyan-500/20 transition-colors group">
                        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 flex items-center justify-center group-hover:bg-cyan-500/30">
                            <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </div>
                        <span class="text-sm font-medium text-cyan-500">New Lease</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>