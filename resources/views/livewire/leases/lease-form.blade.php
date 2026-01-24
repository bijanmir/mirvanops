<div class="max-w-3xl mx-auto">
    <a href="{{ route('leases.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Leases
    </a>

    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-primary">
            <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $lease ? 'Edit Lease' : 'New Lease' }}</h1>
            <p class="text-muted mt-1">{{ $lease ? 'Update lease details' : 'Create a new rental agreement' }}</p>
        </div>

        <form wire:submit="save" class="p-4 sm:p-6 space-y-6">
            <!-- Property & Unit -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Property & Unit</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Property *</label>
                        <select wire:model.live="property_id" class="input-field w-full px-4 py-3 rounded-xl">
                            <option value="">Select Property</option>
                            @foreach($properties as $property)
                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                            @endforeach
                        </select>
                        @error('property_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Unit *</label>
                        <select wire:model.live="unit_id" class="input-field w-full px-4 py-3 rounded-xl" @if(!$property_id) disabled @endif>
                            <option value="">Select Unit</option>
                            @foreach($units as $unit)
                            <option value="{{ $unit->id }}">Unit {{ $unit->unit_number }} @if($unit->status === 'vacant')(Vacant)@else({{ ucfirst($unit->status) }})@endif</option>
                            @endforeach
                        </select>
                        @error('unit_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Tenant -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Tenant</h3>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Select Tenant *</label>
                    <select wire:model="tenant_id" class="input-field w-full px-4 py-3 rounded-xl">
                        <option value="">Select Tenant</option>
                        @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->full_name }} ({{ $tenant->email ?? $tenant->phone ?? 'No contact' }})</option>
                        @endforeach
                    </select>
                    @error('tenant_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-2 text-xs text-muted">Don't see the tenant? <a href="{{ route('tenants.create') }}" class="text-accent hover:underline">Add a new tenant first</a></p>
                </div>
            </div>

            <!-- Lease Term -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Lease Term</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Start Date *</label>
                        <input wire:model="start_date" type="date" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('start_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">End Date *</label>
                        <input wire:model="end_date" type="date" class="input-field w-full px-4 py-3 rounded-xl">
                        @error('end_date') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-secondary mb-3">Lease Type</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="setLeaseType('fixed')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($lease_type === 'fixed') border-accent bg-accent/10 text-accent @else border-primary bg-input text-secondary hover:border-secondary @endif">
                            <span class="block font-semibold">Fixed Term</span>
                            <span class="text-xs opacity-75">Standard lease period</span>
                        </button>
                        <button type="button" wire:click="setLeaseType('month_to_month')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($lease_type === 'month_to_month') border-accent bg-accent/10 text-accent @else border-primary bg-input text-secondary hover:border-secondary @endif">
                            <span class="block font-semibold">Month-to-Month</span>
                            <span class="text-xs opacity-75">Flexible renewal</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Financial -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Financial</h3>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Monthly Rent *</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="rent_amount" type="number" step="0.01" min="0" placeholder="1500" class="input-field w-full pl-8 pr-4 py-3 rounded-xl">
                        </div>
                        @error('rent_amount') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Security Deposit</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="security_deposit" type="number" step="0.01" min="0" placeholder="1500" class="input-field w-full pl-8 pr-4 py-3 rounded-xl">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Payment Due Day</label>
                        <select wire:model="payment_due_day" class="input-field w-full px-4 py-3 rounded-xl">
                            @for($i = 1; $i <= 28; $i++)
                            <option value="{{ $i }}">{{ $i }}{{ $i == 1 ? 'st' : ($i == 2 ? 'nd' : ($i == 3 ? 'rd' : 'th')) }} of month</option>
                            @endfor
                        </select>
                    </div>
                </div>
            </div>

            <!-- Status -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Status</h3>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    <button type="button" wire:click="setStatus('pending')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'pending') border-amber-500 bg-amber-500/10 text-amber-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>Pending</span>
                    </button>
                    <button type="button" wire:click="setStatus('active')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'active') border-green-500 bg-green-500/10 text-green-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>Active</span>
                    </button>
                    <button type="button" wire:click="setStatus('expired')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'expired') border-gray-500 bg-gray-500/10 text-gray-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-gray-500 mr-2"></span>Expired</span>
                    </button>
                    <button type="button" wire:click="setStatus('terminated')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'terminated') border-red-500 bg-red-500/10 text-red-500 @else border-primary bg-input text-secondary hover:border-secondary @endif">
                        <span class="flex items-center justify-center"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>Terminated</span>
                    </button>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-secondary mb-2">Notes</label>
                <textarea wire:model="notes" rows="3" placeholder="Any additional notes about this lease..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse sm:flex-row gap-3 pt-4">
                <a href="{{ route('leases.index') }}" class="btn-secondary flex-1 px-6 py-3 rounded-xl font-medium text-center">Cancel</a>
                <button type="submit" class="btn-primary flex-1 px-6 py-3 rounded-xl font-medium flex items-center justify-center">
                    <span wire:loading.remove wire:target="save">{{ $lease ? 'Update Lease' : 'Create Lease' }}</span>
                    <span wire:loading wire:target="save">Saving...</span>
                </button>
            </div>
        </form>
    </div>
</div>
