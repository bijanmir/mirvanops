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
                            <option value="{{ $unit->id }}">
                                Unit {{ $unit->unit_number }} 
                                @if($unit->market_rent)({{ '$' . number_format($unit->market_rent) }}/mo)@endif
                                - {{ ucfirst($unit->status) }}
                            </option>
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
                    <select wire:model.live="tenant_id" class="input-field w-full px-4 py-3 rounded-xl">
                        <option value="">Select Tenant</option>
                        @foreach($tenants as $tenant)
                        <option value="{{ $tenant->id }}">
                            {{ $tenant->full_name }} 
                            ({{ $tenant->email ?? $tenant->phone ?? 'No contact' }})
                            @if($tenant->leases_count > 0)
                                — ⚠️ {{ $tenant->leases_count }} active lease{{ $tenant->leases_count > 1 ? 's' : '' }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                    @error('tenant_id') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    <p class="mt-2 text-xs text-muted">Don't see the tenant? <a href="{{ route('tenants.create') }}" class="text-accent hover:underline">Add a new tenant first</a></p>
                </div>
                
                <!-- Existing Leases Warning -->
                @if($tenantExistingLeases && count($tenantExistingLeases) > 0)
                <div class="mt-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">This tenant already has {{ count($tenantExistingLeases) }} active lease{{ count($tenantExistingLeases) > 1 ? 's' : '' }}</p>
                            <div class="mt-2 space-y-1">
                                @foreach($tenantExistingLeases as $existingLease)
                                <p class="text-xs text-muted">
                                    • {{ $existingLease->unit->property->name }} - Unit {{ $existingLease->unit->unit_number }}
                                    <span class="text-secondary">({{ ucfirst($existingLease->status) }}, ${{ number_format($existingLease->rent_amount) }}/mo)</span>
                                </p>
                                @endforeach
                            </div>
                            <p class="text-xs text-muted mt-2">You can still create this lease if the tenant is renting multiple units (e.g., apartment + parking/storage).</p>
                        </div>
                    </div>
                </div>
                @endif
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
                        @if($selectedUnitRent && $rent_amount != $selectedUnitRent)
                        <p class="mt-1 text-xs text-amber-500">Unit's listed rent: ${{ number_format($selectedUnitRent) }}</p>
                        <button type="button" wire:click="useUnitRent" class="text-xs text-accent hover:underline">Use unit's rent</button>
                        @endif
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
                
                @if($rent_amount && $selectedUnitRent && $rent_amount != $selectedUnitRent)
                <div class="mt-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model="update_unit_rent" type="checkbox" class="w-5 h-5 rounded border-primary text-accent focus:ring-accent">
                        <span class="text-sm text-secondary">Update unit's market rent to ${{ number_format($rent_amount, 2) }}</span>
                    </label>
                </div>
                @endif
            </div>

            <!-- Pet Information -->
            <div>
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Pet Information</h3>
                <div class="mb-4">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input wire:model.live="has_pet" type="checkbox" class="w-5 h-5 rounded border-primary text-accent focus:ring-accent">
                        <span class="text-sm font-medium text-secondary">Tenant has a pet 🐾</span>
                    </label>
                </div>
                
                @if($has_pet)
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl bg-input animate-fade-in">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Pet Type</label>
                        <select wire:model="pet_type" class="input-field w-full px-4 py-3 rounded-xl">
                            <option value="">Select Type</option>
                            <option value="dog">🐕 Dog</option>
                            <option value="cat">🐈 Cat</option>
                            <option value="bird">🐦 Bird</option>
                            <option value="fish">🐟 Fish</option>
                            <option value="other">🐾 Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Pet Deposit</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="pet_deposit" type="number" step="0.01" min="0" placeholder="500" class="input-field w-full pl-8 pr-4 py-3 rounded-xl">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Monthly Pet Rent</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="pet_rent" type="number" step="0.01" min="0" placeholder="50" class="input-field w-full pl-8 pr-4 py-3 rounded-xl">
                        </div>
                    </div>
                </div>
                @endif
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

            <!-- Summary -->
            @if($rent_amount || $pet_rent)
            <div class="p-4 rounded-xl bg-accent/10 border border-accent/20">
                <h4 class="text-sm font-semibold text-accent mb-2">Monthly Summary</h4>
                <div class="space-y-1 text-sm">
                    <div class="flex justify-between">
                        <span class="text-secondary">Base Rent</span>
                        <span class="text-primary font-medium">${{ number_format($rent_amount ?: 0, 2) }}</span>
                    </div>
                    @if($has_pet && $pet_rent)
                    <div class="flex justify-between">
                        <span class="text-secondary">Pet Rent</span>
                        <span class="text-primary font-medium">${{ number_format($pet_rent, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between pt-2 border-t border-accent/20">
                        <span class="text-secondary font-semibold">Total Monthly</span>
                        <span class="text-accent font-bold">${{ number_format(($rent_amount ?: 0) + ($has_pet ? ($pet_rent ?: 0) : 0), 2) }}</span>
                    </div>
                </div>
            </div>
            @endif

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
