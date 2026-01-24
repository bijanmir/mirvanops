<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">Leases</h1>
            <p class="text-muted mt-1">Manage tenant leases and rental agreements</p>
        </div>
        <a href="{{ route('leases.create') }}" class="btn-primary inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            New Lease
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
            <p class="text-xs text-muted">Total Leases</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-500">{{ $stats['active'] }}</p>
            <p class="text-xs text-muted">Active</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-amber-500">
            <p class="text-2xl font-bold text-amber-500">{{ $stats['pending'] }}</p>
            <p class="text-xs text-muted">Pending</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-red-500">
            <p class="text-2xl font-bold text-red-500">{{ $stats['expiring_soon'] }}</p>
            <p class="text-xs text-muted">Expiring Soon</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-500">${{ number_format($stats['monthly_revenue']) }}</p>
            <p class="text-xs text-muted">Monthly Revenue</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search leases..." class="input-field w-full pl-12 pr-4 py-3 rounded-xl">
            </div>
            <select wire:model.live="statusFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="expired">Expired</option>
                <option value="terminated">Terminated</option>
            </select>
            <select wire:model.live="propertyFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Properties</option>
                @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Leases List -->
    @if($leases->count() > 0)
    <div class="space-y-4">
        @foreach($leases as $lease)
        <div class="glass-card rounded-xl p-4 sm:p-5 hover:shadow-lg transition-all">
            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <!-- Tenant Info -->
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                        <span class="text-lg font-bold text-white">{{ substr($lease->tenant->first_name ?? 'N', 0, 1) }}{{ substr($lease->tenant->last_name ?? 'A', 0, 1) }}</span>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-semibold text-primary truncate">{{ $lease->tenant->full_name ?? 'No Tenant' }}</h3>
                        <p class="text-sm text-muted truncate">{{ $lease->unit->property->name ?? 'N/A' }} - Unit {{ $lease->unit->unit_number ?? 'N/A' }}</p>
                    </div>
                </div>

                <!-- Lease Details -->
                <div class="flex flex-wrap items-center gap-4 sm:gap-6">
                    <div class="text-center">
                        <p class="text-lg font-bold text-primary">${{ number_format($lease->rent_amount) }}</p>
                        <p class="text-xs text-muted">Monthly</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-secondary">{{ $lease->start_date->format('M d, Y') }}</p>
                        <p class="text-xs text-muted">Start Date</p>
                    </div>
                    <div class="text-center">
                        <p class="text-sm font-medium text-secondary">{{ $lease->end_date->format('M d, Y') }}</p>
                        <p class="text-xs text-muted">End Date</p>
                    </div>
                    <div>
                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                            @if($lease->status === 'active') bg-green-500/15 text-green-500
                            @elseif($lease->status === 'pending') bg-amber-500/15 text-amber-500
                            @elseif($lease->status === 'expired') bg-gray-500/15 text-gray-500
                            @else bg-red-500/15 text-red-500 @endif">
                            {{ ucfirst($lease->status) }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-2 sm:flex-shrink-0">
                    <a href="{{ route('leases.edit', $lease) }}" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">Edit</a>
                    <button wire:click="confirmDelete({{ $lease->id }})" class="p-2 text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">{{ $leases->links() }}</div>
    @else
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-blue-500/15 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
        </div>
        @if($search || $statusFilter || $propertyFilter)
        <h3 class="text-xl font-semibold text-primary mb-2">No leases found</h3>
        <p class="text-muted mb-6">Try adjusting your filters.</p>
        <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('propertyFilter', '')" class="btn-secondary px-5 py-2.5 rounded-xl text-sm font-medium">Clear Filters</button>
        @else
        <h3 class="text-xl font-semibold text-primary mb-2">No leases yet</h3>
        <p class="text-muted mb-6">Create your first lease to connect a tenant to a unit.</p>
        <a href="{{ route('leases.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Create First Lease
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
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Lease?</h3>
            <p class="text-muted text-center mb-6">This will remove the lease and set the unit back to vacant.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deleteLease" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
