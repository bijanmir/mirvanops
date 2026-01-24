<div>
    <!-- Back Link -->
    <a href="{{ route('properties.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Properties
    </a>

    <!-- Property Header -->
    <div class="glass-card rounded-2xl p-4 sm:p-6 mb-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div class="flex items-start gap-4">
                <div class="w-14 h-14 rounded-xl @if($property->type === 'residential') bg-emerald-500/15 text-emerald-500 @elseif($property->type === 'commercial') bg-blue-500/15 text-blue-500 @else bg-purple-500/15 text-purple-500 @endif flex items-center justify-center flex-shrink-0">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div>
                    <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-1">
                        <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $property->name }}</h1>
                        <span class="px-3 py-1 text-xs font-medium rounded-full @if($property->type === 'residential') badge-success @elseif($property->type === 'commercial') badge-info @else bg-purple-500/15 text-purple-500 @endif">
                            {{ ucfirst($property->type) }}
                        </span>
                    </div>
                    <p class="text-muted flex items-center text-sm sm:text-base">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ $property->full_address }}
                    </p>
                </div>
            </div>
            <a href="{{ route('properties.edit', $property) }}" class="btn-secondary inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit Property
            </a>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t border-primary">
            <div class="glass-card rounded-xl p-4 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-primary">{{ $property->units->count() }}</p>
                <p class="text-xs sm:text-sm text-muted">Total Units</p>
            </div>
            <div class="glass-card rounded-xl p-4 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-green-500">{{ $property->units->where('status', 'occupied')->count() }}</p>
                <p class="text-xs sm:text-sm text-muted">Occupied</p>
            </div>
            <div class="glass-card rounded-xl p-4 text-center">
                <p class="text-2xl sm:text-3xl font-bold text-amber-500">{{ $property->units->where('status', 'vacant')->count() }}</p>
                <p class="text-xs sm:text-sm text-muted">Vacant</p>
            </div>
            <div class="glass-card rounded-xl p-4 text-center">
                @php
                    $totalRent = $property->units->where('status', 'occupied')->sum('market_rent');
                @endphp
                <p class="text-2xl sm:text-3xl font-bold text-primary">${{ number_format($totalRent) }}</p>
                <p class="text-xs sm:text-sm text-muted">Monthly Rent</p>
            </div>
        </div>
    </div>

    <!-- Units Section -->
    <div class="mb-6">
        <!-- Units Header -->
        <div class="flex flex-col gap-4 mb-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-primary">Units</h2>
                <button wire:click="openUnitModal" class="btn-primary inline-flex items-center justify-center px-4 py-2 rounded-xl text-sm font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Unit
                </button>
            </div>

            <!-- Status Filter -->
            <div class="flex flex-wrap gap-2">
                <button wire:click="$set('statusFilter', '')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all @if($statusFilter === '') bg-accent dark:text-white shadow-md @else bg-input text-secondary hover:text-primary border border-primary @endif">
                    All ({{ $property->units->count() }})
                </button>
                <button wire:click="$set('statusFilter', 'occupied')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all @if($statusFilter === 'occupied') bg-green-500 text-white shadow-md @else bg-input text-secondary hover:text-primary border border-primary @endif">
                    Occupied ({{ $property->units->where('status', 'occupied')->count() }})
                </button>
                <button wire:click="$set('statusFilter', 'vacant')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all @if($statusFilter === 'vacant') bg-amber-500 text-white shadow-md @else bg-input text-secondary hover:text-primary border border-primary @endif">
                    Vacant ({{ $property->units->where('status', 'vacant')->count() }})
                </button>
                <button wire:click="$set('statusFilter', 'maintenance')" class="px-4 py-2 text-sm font-medium rounded-lg transition-all @if($statusFilter === 'maintenance') bg-blue-500 text-white shadow-md @else bg-input text-secondary hover:text-primary border border-primary @endif">
                    Maintenance ({{ $property->units->where('status', 'maintenance')->count() }})
                </button>
            </div>
        </div>

        <!-- Units Grid -->
        @php
            $filteredUnits = $statusFilter ? $property->units->where('status', $statusFilter) : $property->units;
        @endphp

        @if($filteredUnits->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($filteredUnits as $unit)
            <div class="glass-card rounded-xl overflow-hidden group hover:shadow-lg transition-all">
                <!-- Unit Header -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-1">
                        <h3 class="text-lg font-bold text-primary">Unit {{ $unit->unit_number }}</h3>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full 
                            @if($unit->status === 'occupied') bg-green-500/15 text-green-500 
                            @elseif($unit->status === 'vacant') bg-amber-500/15 text-amber-500 
                            @elseif($unit->status === 'maintenance') bg-blue-500/15 text-blue-500 
                            @else bg-red-500/15 text-red-500 @endif">
                            {{ ucfirst($unit->status) }}
                        </span>
                    </div>
                    @if($unit->market_rent)
                    <p class="text-2xl font-bold text-primary">${{ number_format($unit->market_rent) }}<span class="text-sm font-normal text-muted">/mo</span></p>
                    @else
                    <p class="text-muted text-sm">Rent not set</p>
                    @endif
                </div>

                <!-- Unit Details -->
                <div class="px-4 pb-4">
                    <div class="flex items-center justify-between py-3 border-t border-b border-primary">
                        <div class="flex-1 text-center">
                            <p class="text-lg font-semibold text-primary">{{ $unit->beds ?? '-' }}</p>
                            <p class="text-xs text-muted">Beds</p>
                        </div>
                        <div class="w-px h-8 bg-primary"></div>
                        <div class="flex-1 text-center">
                            <p class="text-lg font-semibold text-primary">{{ $unit->baths ?? '-' }}</p>
                            <p class="text-xs text-muted">Baths</p>
                        </div>
                        <div class="w-px h-8 bg-primary"></div>
                        <div class="flex-1 text-center">
                            <p class="text-lg font-semibold text-primary">{{ $unit->sqft ? number_format($unit->sqft) : '-' }}</p>
                            <p class="text-xs text-muted">Sq Ft</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mt-4 mb-4">
                        <button wire:click="openUnitModal({{ $unit->id }})" class="flex-1 btn-secondary px-3 py-2.5 rounded-lg text-sm font-medium flex items-center justify-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Edit
                        </button>
                        <button wire:click="confirmDeleteUnit({{ $unit->id }})" class="px-3 py-2.5 btn-secondary rounded-lg hover:bg-red-500/10 hover:border-red-500/50 hover:text-red-500 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
            <div class="w-16 h-16 rounded-full bg-input flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
            </div>
            @if($statusFilter)
            <h3 class="text-lg font-semibold text-primary mb-2">No {{ $statusFilter }} units</h3>
            <p class="text-muted mb-6">There are no units with this status.</p>
            <button wire:click="$set('statusFilter', '')" class="btn-secondary inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium">
                View All Units
            </button>
            @else
            <h3 class="text-lg font-semibold text-primary mb-2">No units yet</h3>
            <p class="text-muted mb-6">Add units to start managing this property.</p>
            <button wire:click="openUnitModal" class="btn-primary inline-flex items-center px-5 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                Add First Unit
            </button>
            @endif
        </div>
        @endif
    </div>

    <!-- Unit Modal -->
    @if($showUnitModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="closeUnitModal"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto animate-fade-in">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-primary">{{ $editingUnit ? 'Edit Unit' : 'Add Unit' }}</h3>
                <button wire:click="closeUnitModal" class="p-2 text-muted hover:text-primary hover:bg-input rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form wire:submit="saveUnit" class="space-y-4">
                <!-- Unit Number -->
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Unit Number *</label>
                    <input wire:model="unit_number" type="text" placeholder="e.g., 101, A, 2B" class="input-field w-full px-4 py-3 rounded-xl">
                    @error('unit_number') <p class="mt-1 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>

                <!-- Beds / Baths -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Beds</label>
                        <input wire:model="beds" type="number" step="0.5" min="0" placeholder="2" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Baths</label>
                        <input wire:model="baths" type="number" step="0.5" min="0" placeholder="1" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                </div>

                <!-- Sq Ft / Rent -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Square Feet</label>
                        <input wire:model="sqft" type="number" min="0" placeholder="850" class="input-field w-full px-4 py-3 rounded-xl">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-secondary mb-2">Market Rent</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted">$</span>
                            <input wire:model="market_rent" type="number" step="0.01" min="0" placeholder="1500" class="input-field w-full pl-8 pr-4 py-3 rounded-xl">
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-secondary mb-3">Status</label>
                    <div class="grid grid-cols-2 gap-2">
                        <button type="button" wire:click="setUnitStatus('vacant')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'vacant') border-amber-500 bg-amber-500/10 text-amber-500 @else border-primary bg-input text-muted hover:border-secondary @endif">
                            <span class="flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-amber-500 mr-2"></span>
                                Vacant
                            </span>
                        </button>
                        <button type="button" wire:click="setUnitStatus('occupied')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'occupied') border-green-500 bg-green-500/10 text-green-500 @else border-primary bg-input text-muted hover:border-secondary @endif">
                            <span class="flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-green-500 mr-2"></span>
                                Occupied
                            </span>
                        </button>
                        <button type="button" wire:click="setUnitStatus('maintenance')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'maintenance') border-blue-500 bg-blue-500/10 text-blue-500 @else border-primary bg-input text-muted hover:border-secondary @endif">
                            <span class="flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span>
                                Maintenance
                            </span>
                        </button>
                        <button type="button" wire:click="setUnitStatus('offline')" class="p-3 rounded-xl border-2 text-sm font-medium transition-all @if($status === 'offline') border-red-500 bg-red-500/10 text-red-500 @else border-primary bg-input text-muted hover:border-secondary @endif">
                            <span class="flex items-center justify-center">
                                <span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span>
                                Offline
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-medium text-secondary mb-2">Notes</label>
                    <textarea wire:model="unit_notes" rows="2" placeholder="Any notes about this unit..." class="input-field w-full px-4 py-3 rounded-xl resize-none"></textarea>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="closeUnitModal" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 btn-primary px-4 py-3 rounded-xl font-medium">
                        <span wire:loading.remove wire:target="saveUnit">{{ $editingUnit ? 'Update Unit' : 'Add Unit' }}</span>
                        <span wire:loading wire:target="saveUnit">Saving...</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Unit Modal -->
    @if($showDeleteUnitModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDeleteUnit"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-md animate-fade-in">
            <div class="w-14 h-14 rounded-full badge-danger flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Unit?</h3>
            <p class="text-muted text-center mb-6">This will permanently delete this unit and all associated data. This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDeleteUnit" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">
                    Cancel
                </button>
                <button wire:click="deleteUnit" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @endif
</div>