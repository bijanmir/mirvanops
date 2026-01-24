<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">Vendors</h1>
            <p class="text-muted mt-1">Manage your contractors and service providers</p>
        </div>
        <a href="{{ route('vendors.create') }}" class="btn-primary inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Vendor
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
            <p class="text-xs text-muted">Total Vendors</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-500">{{ $stats['active'] }}</p>
            <p class="text-xs text-muted">Active</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-gray-500">
            <p class="text-2xl font-bold text-gray-500">{{ $stats['inactive'] }}</p>
            <p class="text-xs text-muted">Inactive</p>
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
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search vendors..." class="input-field w-full pl-12 pr-4 py-3 rounded-xl">
            </div>

            <!-- Specialty Filter -->
            <select wire:model.live="specialtyFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Specialties</option>
                @foreach($specialties as $specialty)
                <option value="{{ $specialty }}">{{ $specialty }}</option>
                @endforeach
            </select>

            <!-- Status Filter -->
            <select wire:model.live="statusFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>

    <!-- Vendors Grid -->
    @if($vendors->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($vendors as $vendor)
        <div class="glass-card rounded-xl overflow-hidden hover:shadow-lg transition-all">
            <div class="p-5">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-lg font-bold text-white">{{ substr($vendor->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-primary">{{ $vendor->name }}</h3>
                            <span class="text-xs px-2 py-0.5 rounded-full @if($vendor->is_active) bg-green-500/15 text-green-500 @else bg-gray-500/15 text-gray-500 @endif">
                                {{ $vendor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <span class="text-secondary">{{ $vendor->specialty }}</span>
                    </div>
                    @if($vendor->phone)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="tel:{{ $vendor->phone }}" class="text-secondary hover:text-accent transition-colors">{{ $vendor->phone }}</a>
                    </div>
                    @endif
                    @if($vendor->email)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:{{ $vendor->email }}" class="text-secondary hover:text-accent transition-colors truncate">{{ $vendor->email }}</a>
                    </div>
                    @endif
                    @if($vendor->hourly_rate)
                    <div class="flex items-center text-sm">
                        <svg class="w-4 h-4 mr-2 text-muted flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="text-secondary">${{ number_format($vendor->hourly_rate, 2) }}/hr</span>
                    </div>
                    @endif
                </div>

                <!-- Stats -->
                <div class="flex items-center gap-4 py-3 border-t border-primary">
                    <div class="text-center flex-1">
                        <p class="text-lg font-semibold text-primary">{{ $vendor->maintenance_requests_count }}</p>
                        <p class="text-xs text-muted">Total Jobs</p>
                    </div>
                    <div class="w-px h-8 bg-primary"></div>
                    <div class="text-center flex-1">
                        <p class="text-lg font-semibold text-amber-500">{{ $vendor->open_requests_count }}</p>
                        <p class="text-xs text-muted">Open</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-3 border-t border-primary">
                    <a href="{{ route('vendors.edit', $vendor) }}" class="flex-1 btn-secondary px-3 py-2 rounded-lg text-sm font-medium text-center">
                        Edit
                    </a>
                    <button wire:click="toggleStatus({{ $vendor->id }})" class="px-3 py-2 rounded-lg text-sm font-medium transition-colors @if($vendor->is_active) bg-gray-500/10 text-gray-500 hover:bg-gray-500/20 @else bg-green-500/10 text-green-500 hover:bg-green-500/20 @endif">
                        {{ $vendor->is_active ? 'Deactivate' : 'Activate' }}
                    </button>
                    <button wire:click="confirmDelete({{ $vendor->id }})" class="p-2 text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
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
        {{ $vendors->links() }}
    </div>
    @else
    <!-- Empty State -->
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-emerald-500/15 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        @if($search || $specialtyFilter || $statusFilter)
        <h3 class="text-xl font-semibold text-primary mb-2">No vendors found</h3>
        <p class="text-muted mb-6">Try adjusting your filters.</p>
        <button wire:click="$set('search', ''); $set('specialtyFilter', ''); $set('statusFilter', '')" class="btn-secondary px-5 py-2.5 rounded-xl text-sm font-medium">Clear Filters</button>
        @else
        <h3 class="text-xl font-semibold text-primary mb-2">No vendors yet</h3>
        <p class="text-muted mb-6">Add your first vendor to start assigning maintenance requests.</p>
        <a href="{{ route('vendors.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add First Vendor
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
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Vendor?</h3>
            <p class="text-muted text-center mb-6">This will remove the vendor from all maintenance requests. This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deleteVendor" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>