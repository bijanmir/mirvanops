<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">Tenants</h1>
            <p class="text-muted mt-1">Manage your tenants and their information</p>
        </div>
        <a href="{{ route('tenants.create') }}" class="btn-primary inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Tenant
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
            <p class="text-xs text-muted">Total Tenants</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-500">{{ $stats['active'] }}</p>
            <p class="text-xs text-muted">Active</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-amber-500">
            <p class="text-2xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
            <p class="text-xs text-muted">Pending</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-gray-500">
            <p class="text-2xl font-bold text-gray-500">{{ $stats['past'] }}</p>
            <p class="text-xs text-muted">Past</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search tenants..." class="input-field w-full pl-12 pr-4 py-3 rounded-xl">
            </div>

            <!-- Status Filter -->
            <select wire:model.live="statusFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="past">Past</option>
                <option value="evicted">Evicted</option>
            </select>

            <!-- Property Filter -->
            <select wire:model.live="propertyFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Properties</option>
                @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Tenants List -->
    @if($tenants->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($tenants as $tenant)
        <div class="glass-card rounded-xl overflow-hidden hover:shadow-lg transition-all">
            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-white">{{ substr($tenant->first_name, 0, 1) }}{{ substr($tenant->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary">{{ $tenant->full_name }}</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full 
                                @if($tenant->status === 'active') bg-green-500/15 text-green-500 
                                @elseif($tenant->status === 'pending') bg-amber-500/15 text-amber-500 
                                @elseif($tenant->status === 'past') bg-gray-500/15 text-gray-500 
                                @else bg-red-500/15 text-red-500 @endif">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    @if($tenant->phone)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="tel:{{ $tenant->phone }}" class="text-secondary hover:text-accent transition-colors">{{ $tenant->phone }}</a>
                    </div>
                    @endif
                    @if($tenant->email)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:{{ $tenant->email }}" class="text-secondary hover:text-accent transition-colors truncate">{{ $tenant->email }}</a>
                    </div>
                    @endif
                    @php
                        $activeLease = $tenant->leases->first();
                    @endphp
                    @if($activeLease)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-secondary">{{ $activeLease->unit->property->name }} - Unit {{ $activeLease->unit->unit_number }}</span>
                    </div>
                    @else
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <span class="text-muted italic">No active lease</span>
                    </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-3 border-t border-primary">
                    <a href="{{ route('tenants.show', $tenant) }}" class="flex-1 btn-secondary px-3 py-2 rounded-lg text-sm font-medium text-center">
                        View
                    </a>
                    <a href="{{ route('tenants.edit', $tenant) }}" class="flex-1 btn-secondary px-3 py-2 rounded-lg text-sm font-medium text-center">
                        Edit
                    </a>
                    <button wire:click="confirmDelete({{ $tenant->id }})" class="p-2 text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
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
    <div class="mt-6">
        {{ $tenants->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-indigo-500/15 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </div>
        @if($search || $statusFilter || $propertyFilter)
        <h3 class="text-xl font-semibold text-primary mb-2">No tenants found</h3>
        <p class="text-muted mb-6">Try adjusting your filters.</p>
        <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('propertyFilter', '')" class="btn-secondary px-5 py-2.5 rounded-xl text-sm font-medium">Clear Filters</button>
        @else
        <h3 class="text-xl font-semibold text-primary mb-2">No tenants yet</h3>
        <p class="text-muted mb-6">Add your first tenant to get started.</p>
        <a href="{{ route('tenants.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add First Tenant
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
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Tenant?</h3>
            <p class="text-muted text-center mb-6">This will remove the tenant and their history. This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deleteTenant" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>