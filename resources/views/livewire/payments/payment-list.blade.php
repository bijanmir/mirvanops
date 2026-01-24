<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">Payments</h1>
            <p class="text-muted mt-1">Track and manage rent payments</p>
        </div>
        <a href="{{ route('payments.create') }}" class="btn-primary inline-flex items-center justify-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Record Payment
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-6">
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-green-500">${{ number_format($stats['total_collected']) }}</p>
            <p class="text-xs text-muted">Collected</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-primary">${{ number_format($stats['expected_rent']) }}</p>
            <p class="text-xs text-muted">Expected</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-accent">
            <p class="text-2xl font-bold text-accent">{{ $stats['collection_rate'] }}%</p>
            <p class="text-xs text-muted">Collection Rate</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-500">{{ $stats['payment_count'] }}</p>
            <p class="text-xs text-muted">Payments</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-amber-500">
            <p class="text-2xl font-bold text-amber-500">{{ $stats['outstanding_count'] }}</p>
            <p class="text-xs text-muted">Outstanding</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-red-500">
            <p class="text-2xl font-bold text-red-500">${{ number_format($stats['total_late_fees']) }}</p>
            <p class="text-xs text-muted">Late Fees</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tenant or ref #..." class="input-field w-full pl-12 pr-4 py-3 rounded-xl">
            </div>
            <div>
                <input wire:model.live="monthFilter" type="month" class="input-field w-full px-4 py-3 rounded-xl">
            </div>
            <select wire:model.live="methodFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Methods</option>
                @foreach($paymentMethods as $value => $label)
                <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            <select wire:model.live="statusFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Statuses</option>
                <option value="completed">Completed</option>
                <option value="pending">Pending</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>
            <select wire:model.live="propertyFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Properties</option>
                @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Payments List -->
    @if($payments->count() > 0)
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-primary">
                        <th class="text-left text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Tenant</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Property / Unit</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Period</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Method</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Amount</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Status</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Date</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase tracking-wide px-6 py-4">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @foreach($payments as $payment)
                    <tr class="hover:bg-input/50">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                                    <span class="text-xs font-bold text-white">{{ substr($payment->tenant->first_name ?? 'T', 0, 1) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-medium text-primary truncate">{{ $payment->tenant->full_name ?? 'Unknown' }}</p>
                                    @if($payment->reference_number)
                                    <p class="text-xs text-muted">Ref: {{ $payment->reference_number }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($payment->lease && $payment->lease->unit)
                            <p class="text-sm text-primary">{{ $payment->lease->unit->property->name ?? 'N/A' }}</p>
                            <p class="text-xs text-muted">Unit {{ $payment->lease->unit->unit_number }}</p>
                            @else
                            <p class="text-sm text-muted">N/A</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-primary">{{ $payment->period_covered }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full bg-input text-secondary">
                                @if($payment->payment_method === 'ach')
                                    🏦
                                @elseif($payment->payment_method === 'check')
                                    📝
                                @elseif($payment->payment_method === 'cash')
                                    💵
                                @elseif($payment->payment_method === 'zelle')
                                    ⚡
                                @elseif($payment->payment_method === 'venmo')
                                    📱
                                @elseif($payment->payment_method === 'card')
                                    💳
                                @else
                                    📋
                                @endif
                                {{ $payment->payment_method_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <p class="text-sm font-semibold text-primary">${{ number_format($payment->amount, 2) }}</p>
                            @if($payment->late_fee)
                            <p class="text-xs text-red-500">+${{ number_format($payment->late_fee, 2) }} late fee</p>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-medium rounded-full
                                @if($payment->status === 'completed') bg-green-500/15 text-green-500
                                @elseif($payment->status === 'pending') bg-amber-500/15 text-amber-500
                                @elseif($payment->status === 'failed') bg-red-500/15 text-red-500
                                @else bg-gray-500/15 text-gray-500
                                @endif">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-primary">{{ $payment->payment_date->format('M d, Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('payments.edit', $payment) }}" class="p-2 text-muted hover:text-primary hover:bg-input rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </a>
                                <button wire:click="confirmDelete({{ $payment->id }})" class="p-2 text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors" title="Delete">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
    @else
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-green-500/15 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        @if($search || $statusFilter || $methodFilter || $propertyFilter)
        <h3 class="text-xl font-semibold text-primary mb-2">No payments found</h3>
        <p class="text-muted mb-6">Try adjusting your filters or search terms.</p>
        <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('methodFilter', ''); $set('propertyFilter', '')" class="btn-secondary px-6 py-3 rounded-xl font-medium">Clear Filters</button>
        @else
        <h3 class="text-xl font-semibold text-primary mb-2">No payments recorded</h3>
        <p class="text-muted mb-6">Start by recording your first rent payment.</p>
        <a href="{{ route('payments.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Record First Payment
        </a>
        @endif
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-md animate-fade-in">
            <div class="w-14 h-14 rounded-full bg-red-500/15 text-red-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Payment?</h3>
            <p class="text-muted text-center mb-6">This will permanently delete this payment record.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deletePayment" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>