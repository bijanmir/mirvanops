<div>
    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 mb-6">
        <div class="flex flex-col sm:flex-row gap-4">
            <!-- Search -->
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-subtle" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Search properties..." 
                    class="input-field w-full pl-12 pr-4 py-3 rounded-xl"
                >
            </div>
            
            <!-- Type Filter -->
            <div class="sm:w-48">
                <select 
                    wire:model.live="typeFilter" 
                    class="input-field w-full px-4 py-3 rounded-xl appearance-none cursor-pointer"
                >
                    <option value="">All Types</option>
                    <option value="residential">Residential</option>
                    <option value="commercial">Commercial</option>
                    <option value="mixed">Mixed Use</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Properties Grid -->
    @if($properties->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-6">
        @foreach($properties as $property)
        <div class="glass-card rounded-2xl overflow-hidden group">
            <!-- Property Header -->
            <div class="p-4 sm:p-6 border-b border-primary">
                <div class="flex items-start justify-between mb-4">
                    <div class="w-12 h-12 rounded-xl badge-info flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $property->type === 'residential' ? 'badge-success' : ($property->type === 'commercial' ? 'badge-info' : 'bg-purple-500/15 text-purple-500') }}">
                        {{ ucfirst($property->type) }}
                    </span>
                </div>
                
                <h3 class="text-lg font-semibold text-primary mb-2 group-hover:text-accent transition-colors">
                    <a href="{{ route('properties.show', $property) }}">{{ $property->name }}</a>
                </h3>
                
                <p class="text-muted text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="truncate">{{ $property->address }}, {{ $property->city }}</span>
                </p>
            </div>

            <!-- Property Stats -->
            <div class="p-4 sm:p-6 bg-input">
                <div class="flex items-center justify-between mb-4">
                    <div class="text-center">
                        <p class="text-2xl font-bold text-primary">{{ $property->units_count }}</p>
                        <p class="text-xs text-muted">Units</p>
                    </div>
                    <div class="text-center">
                        @php
                            $occupiedUnits = $property->units()->where('status', 'occupied')->count();
                        @endphp
                        <p class="text-2xl font-bold text-green-500">{{ $occupiedUnits }}</p>
                        <p class="text-xs text-muted">Occupied</p>
                    </div>
                    <div class="text-center">
                        @php
                            $vacantUnits = $property->units()->where('status', 'vacant')->count();
                        @endphp
                        <p class="text-2xl font-bold text-accent">{{ $vacantUnits }}</p>
                        <p class="text-xs text-muted">Vacant</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2">
                    <a href="{{ route('properties.show', $property) }}" class="btn-secondary flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-center">
                        View Details
                    </a>
                    <a href="{{ route('properties.edit', $property) }}" class="btn-secondary p-2.5 rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>
                    <button wire:click="confirmDelete({{ $property->id }})" class="btn-secondary p-2.5 rounded-xl hover:bg-red-500/10 hover:border-red-500/50 hover:text-red-500 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $properties->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full badge-info flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        @if($search || $typeFilter)
            <h3 class="text-xl font-semibold text-primary mb-2">No properties found</h3>
            <p class="text-muted mb-6">Try adjusting your search or filter criteria.</p>
            <button wire:click="$set('search', ''); $set('typeFilter', '')" class="btn-secondary inline-flex items-center px-5 py-2.5 rounded-xl font-medium">
                Clear Filters
            </button>
        @else
            <h3 class="text-xl font-semibold text-primary mb-2">No properties yet</h3>
            <p class="text-muted mb-6">Get started by adding your first property.</p>
            <a href="{{ route('properties.create') }}" class="btn-primary inline-flex items-center px-6 py-3 text-white font-medium rounded-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                </svg>
                Add Your First Property
            </a>
        @endif
    </div>
    @endif

    <!-- Delete Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-md animate-fade-in">
            <div class="w-14 h-14 rounded-full badge-danger flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Property?</h3>
            <p class="text-muted text-center mb-6">This will permanently delete this property and all its units. This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="btn-secondary flex-1 px-4 py-3 rounded-xl font-medium">
                    Cancel
                </button>
                <button wire:click="deleteProperty" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">
                    Delete
                </button>
            </div>
        </div>
    </div>
    @endif
</div>