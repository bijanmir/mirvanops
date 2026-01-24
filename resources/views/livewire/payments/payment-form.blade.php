<div class="max-w-3xl mx-auto">
    <a href="{{ route('payments.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Payments
    </a>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-primary">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $payment ? 'Edit Payment' : 'Record Payment' }}</h1>
            <p class="text-muted mt-1">{{ $payment ? 'Update payment details' : 'Record a new rent payment' }}</p>
        </div>

        <form wire:submit="save" class="p-4 sm:p-6 space-y-6">
            <!-- Lease Selection -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Lease</h3>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Select Lease *</label>
                    <select wire:model.live="lease_id" class="input-field w-full px-4 py-3 rounded-xl">
                        <option value="">Select a lease...</option>
                        @foreach($leases as $lease)
                        <option value="{{ $lease->id }}">
                            {{ $lease->tenant->full_name ?? 'Unknown' }} — 
                            {{ $lease->unit->property->name ?? '' }} Unit {{ $lease->unit->unit_number ?? '' }}
                            (${{ number_format($lease->rent_amount) }}/mo)
                        </option>
                        @endforeach
                    </select>
                    @error('lease_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Selected Lease Details -->
                @if($selectedLease)
                <div class="mt-4 p-4 rounded-xl bg-input">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-white">{{ substr($selectedLease->tenant->first_name ?? 'T', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-primary">{{ $selectedLease->tenant->full_name ?? 'Unknown' }}</p>
                            <p class="text-xs text-muted">{{ $selectedLease->unit->property->name ?? '' }} - Unit {{ $selectedLease->unit->unit_number ?? '' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold text-primary">${{ number_format($selectedLease->rent_amount + ($selectedLease->pet_rent ?? 0), 2) }}</p>
                            <p class="text-xs text-muted">Monthly Rent{{ $selectedLease->pet_rent ? ' + Pet' : '' }}</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Payment Details -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Payment Details</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Amount *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="amount" type="number" step="0.01" min="0" class="input-field w-full pl-8 pr-4 py-3 rounded-xl" placeholder="0.00">
                        </div>
                        @error('amount') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                        @if($selectedLease && $amount != ($selectedLease->rent_amount + ($selectedLease->pet_rent ?? 0)))
                        <button type="button" wire:click="useFullRent" class="mt-1 text-xs text-accent hover:underline">
                            Use full rent: ${{ number_format($selectedLease->rent_amount + ($selectedLease->pet_rent ?? 0), 2) }}
                        </button>
                        @endif
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Payment Date *</label>
                        <input wire:model="payment_date" type="date" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('payment_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Period Covered *</label>
                        <select wire:model="period_covered" class="input-field w-full px-4 py-3 rounded-xl">
                            @foreach($periods as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('period_covered') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Late Fee</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="late_fee" type="number" step="0.01" min="0" class="input-field w-full pl-8 pr-4 py-3 rounded-xl" placeholder="0.00">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Payment Method</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($paymentMethods as $value => $label)
                    <button type="button" wire:click="setPaymentMethod('{{ $value }}')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($payment_method === $value) border-accent bg-accent/10 text-accent @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="block text-lg mb-1">
                            @if($value === 'ach') 🏦
                            @elseif($value === 'check') 📝
                            @elseif($value === 'cash') 💵
                            @elseif($value === 'money_order') 📋
                            @elseif($value === 'zelle') ⚡
                            @elseif($value === 'venmo') 📱
                            @elseif($value === 'card') 💳
                            @else 📋
                            @endif
                        </span>
                        <span class="text-xs">{{ $label }}</span>
                    </button>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium text-secondary mb-2">Reference Number</label>
                    <input wire:model="reference_number" type="text" class="input-field w-full px-4 py-3 rounded-xl" placeholder="Check #, Confirmation #, etc.">
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Status</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" wire:click="setStatus('completed')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'completed') border-green-500 bg-green-500/10 text-green-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Completed</span>
                    </button>
                    <button type="button" wire:click="setStatus('pending')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'pending') border-amber-500 bg-amber-500/10 text-amber-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>Pending</span>
                    </button>
                    <button type="button" wire:click="setStatus('failed')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'failed') border-red-500 bg-red-500/10 text-red-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>Failed</span>
                    </button>
                    <button type="button" wire:click="setStatus('refunded')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'refunded') border-gray-500 bg-gray-500/10 text-gray-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-gray-500 mr-2"></span>Refunded</span>
                    </button>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Notes</label>
                <textarea wire:model="notes" rows="3" placeholder="Any additional notes..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
            </div>

            <!-- Summary -->
            @if($amount)
            <div class="p-4 rounded-xl bg-green-500/10 border border-green-500/20">
                <h4 class="text-sm font-semibold text-green-500 mb-2">Payment Summary</h4>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-secondary">Rent Payment</span>
                        <span class="text-primary font-medium">${{ number_format($amount, 2) }}</span>
                    </div>
                    @if($late_fee)
                    <div class="flex justify-between">
                        <span class="text-secondary">Late Fee</span>
                        <span class="text-red-500 font-medium">${{ number_format($late_fee, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-green-500/20">
                        <span class="text-secondary font-semibold">Total</span>
                        <span class="text-green-500 font-bold">${{ number_format(($amount ?: 0) + ($late_fee ?: 0), 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('payments.index') }}" class="btn-secondary flex-1 px-6 py-3 rounded-xl font-medium text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 px-6 py-3 rounded-xl font-medium flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $payment ? 'Update Payment' : 'Record Payment' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>