<div>
    <!-- Back Button & Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            <a href="{{ route('properties.index') }}" class="p-2 rounded-lg hover:bg-input transition-colors">
                <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-primary">{{ $property->name }}</h1>
                <p class="text-muted">{{ $property->address }}, {{ $property->city }}, {{ $property->state }} {{ $property->zip }}</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('properties.edit', $property) }}" class="btn-secondary px-4 py-2 rounded-xl text-sm font-medium">Edit Property</a>
            <button wire:click="openUnitModal" class="btn-primary px-4 py-2 rounded-xl text-sm font-medium">Add Unit</button>
        </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
            <p class="text-xs text-muted">Total Units</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-500">{{ $stats['occupied'] }}</p>
            <p class="text-xs text-muted">Occupied</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-500">{{ $stats['vacant'] }}</p>
            <p class="text-xs text-muted">Vacant</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-amber-500">
            <p class="text-2xl font-bold text-amber-500">{{ $stats['maintenance'] }}</p>
            <p class="text-xs text-muted">Maintenance</p>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
        <button wire:click="$set('statusFilter', '')" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap @if(!$statusFilter) bg-accent text-white @else bg-input text-secondary hover:bg-input/80 @endif">
            All Units
        </button>
        <button wire:click="$set('statusFilter', 'occupied')" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap @if($statusFilter === 'occupied') bg-green-500 text-white @else bg-input text-secondary hover:bg-input/80 @endif">
            Occupied
        </button>
        <button wire:click="$set('statusFilter', 'vacant')" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap @if($statusFilter === 'vacant') bg-blue-500 text-white @else bg-input text-secondary hover:bg-input/80 @endif">
            Vacant
        </button>
        <button wire:click="$set('statusFilter', 'maintenance')" class="px-4 py-2 rounded-lg text-sm font-medium whitespace-nowrap @if($statusFilter === 'maintenance') bg-amber-500 text-white @else bg-input text-secondary hover:bg-input/80 @endif">
            Maintenance
        </button>
    </div>

    <!-- Units Grid -->
    @if($units->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($units as $unit)
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="p-4">
                <div class="flex items-start justify-between mb-3">
                    <div>
                        <h3 class="text-lg font-semibold text-primary">Unit {{ $unit->unit_number }}</h3>
                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                            @if($unit->status === 'occupied') bg-green-500/15 text-green-500
                            @elseif($unit->status === 'vacant') bg-blue-500/15 text-blue-500
                            @else bg-amber-500/15 text-amber-500 @endif">
                            {{ ucfirst($unit->status) }}
                        </span>
                    </div>
                    @if($unit->market_rent)
                    <p class="text-lg font-bold text-primary">${{ number_format($unit->market_rent) }}<span class="text-xs text-muted font-normal">/mo</span></p>
                    @endif
                </div>

                <div class="flex items-center gap-4 text-sm text-secondary mb-3">
                    <span class="flex items-center">
                        <svg class="w-4 h-4 mr-1 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        {{ (int)$unit->bedrooms }} bed
                    </span>
                    <span class="text-muted">|</span>
                    <span>{{ number_format($unit->bathrooms, 1) }} bath</span>
                    @if($unit->square_feet)
                    <span class="text-muted">|</span>
                    <span>{{ number_format($unit->square_feet) }} sqft</span>
                    @endif
                </div>

                <!-- Current Lease Info -->
                @if($unit->currentLease)
                @php
                    $lease = $unit->currentLease;
                    $daysUntilExpiry = now()->diffInDays($lease->end_date, false);
                    $isExpiringSoon = $daysUntilExpiry > 0 && $daysUntilExpiry <= 30;
                @endphp
                <div class="p-3 rounded-lg bg-green-500/10 border border-green-500/20 mb-3">
                    <div class="flex items-center gap-2 mb-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                            <span class="text-xs font-bold text-white">{{ substr($lease->tenant->first_name ?? 'T', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-primary truncate">{{ $lease->tenant->full_name ?? 'Unknown Tenant' }}</p>
                            <p class="text-xs text-muted">
                                ${{ number_format($lease->rent_amount) }}/mo
                                @if($lease->has_pet)
                                    <span class="ml-1">🐾</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="text-xs text-muted space-y-1">
                        <div class="flex justify-between">
                            <span>Lease Period</span>
                            <span class="text-secondary">{{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}</span>
                        </div>
                        @if($isExpiringSoon)
                        <p class="text-amber-500 font-medium">⚠️ Expiring in {{ $daysUntilExpiry }} days</p>
                        @endif
                    </div>
                    <div class="mt-2 pt-2 border-t border-green-500/20">
                        <a href="{{ route('leases.edit', $lease) }}" class="text-xs text-accent hover:underline">View/Edit Lease →</a>
                    </div>
                </div>
                @else
                <div class="p-3 rounded-lg bg-blue-500/10 border border-blue-500/20 mb-3">
                    <p class="text-sm text-blue-600 dark:text-blue-400 mb-1">Unit is available</p>
                    <a href="{{ route('leases.create', ['property_id' => $property->id, 'unit_id' => $unit->id]) }}" class="text-xs text-accent hover:underline">Create Lease →</a>
                </div>
                @endif

                <!-- Actions -->
                <div class="flex gap-2 pt-3 border-t border-primary">
                    <button wire:click="openUnitModal({{ $unit->id }})" class="flex-1 btn-secondary px-3 py-2 rounded-lg text-sm font-medium">Edit</button>
                    <button wire:click="confirmDeleteUnit({{ $unit->id }})" class="p-2 text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg @if($unit->currentLease) opacity-50 cursor-not-allowed @endif" @if($unit->currentLease) disabled title="Cannot delete unit with active lease" @endif>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-blue-500/15 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-primary mb-2">No units yet</h3>
        <p class="text-muted mb-6">Add units to this property to start managing them.</p>
        <button wire:click="openUnitModal" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add First Unit
        </button>
    </div>
    @endif

    <!-- Unit Modal -->
    @if($showUnitModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeUnitModal"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto animate-fade-in">
            <h3 class="text-xl font-semibold text-primary mb-6">{{ $editingUnit ? 'Edit Unit' : 'Add Unit' }}</h3>
            
            <form wire:submit="saveUnit" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Unit Number *</label>
                        <input wire:model="unit_number" type="text" class="input-field w-full px-4 py-3 rounded-xl" placeholder="101">
                        @error('unit_number') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Floor</label>
                        <input wire:model="floor" type="text" class="input-field w-full px-4 py-3 rounded-xl" placeholder="1">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Bedrooms *</label>
                        <select wire:model="bedrooms" class="input-field w-full px-4 py-3 rounded-xl">
                            @for($i = 0; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Bathrooms *</label>
                        <select wire:model="bathrooms" class="input-field w-full px-4 py-3 rounded-xl">
                            @foreach([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5] as $bath)
                            <option value="{{ $bath }}">{{ $bath }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Square Feet</label>
                        <input wire:model="square_feet" type="number" class="input-field w-full px-4 py-3 rounded-xl" placeholder="850">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Market Rent</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="market_rent" type="number" step="0.01" class="input-field w-full pl-8 pr-4 py-3 rounded-xl" placeholder="1500">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Status</label>
                    @if($editingUnit && $editingUnit->currentLease)
                    <div class="p-3 rounded-lg bg-amber-500/10 border border-amber-500/20 text-sm">
                        <p class="text-amber-600 dark:text-amber-400">🔒 Status is locked because this unit has an active lease.</p>
                        <p class="text-xs text-muted mt-1">Terminate or expire the lease to change unit status.</p>
                    </div>
                    @else
                    <select wire:model="status" class="input-field w-full px-4 py-3 rounded-xl">
                        <option value="vacant">Vacant</option>
                        <option value="occupied">Occupied</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Description</label>
                    <textarea wire:model="description" rows="2" class="input-field w-full px-4 py-3 rounded-xl resize-none" placeholder="Unit description..."></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeUnitModal" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                    <button type="submit" class="flex-1 btn-primary px-4 py-3 rounded-xl font-medium">{{ $editingUnit ? 'Update' : 'Add' }} Unit</button>
                </div>
            </form>
        </div>
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
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Unit?</h3>
            <p class="text-muted text-center mb-6">This will permanently delete this unit and all its data.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deleteUnit" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
