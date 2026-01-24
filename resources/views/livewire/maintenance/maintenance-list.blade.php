<div>
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-primary">Maintenance Requests</h1>
            <p class="text-muted mt-1">Track and manage all repair requests</p>
        </div>
        <a href="{{ route('maintenance.create') }}" class="btn-primary inline-flex items-center justify-center px-5 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            New Request
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="glass-card rounded-xl p-4 text-center">
            <p class="text-2xl font-bold text-primary">{{ $stats['total'] }}</p>
            <p class="text-xs text-muted">Total</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-amber-500">
            <p class="text-2xl font-bold text-amber-500">{{ $stats['open'] }}</p>
            <p class="text-xs text-muted">Open</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-blue-500">
            <p class="text-2xl font-bold text-blue-500">{{ $stats['new'] }}</p>
            <p class="text-xs text-muted">New</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-purple-500">
            <p class="text-2xl font-bold text-purple-500">{{ $stats['in_progress'] }}</p>
            <p class="text-xs text-muted">In Progress</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-green-500">
            <p class="text-2xl font-bold text-green-500">{{ $stats['completed'] }}</p>
            <p class="text-xs text-muted">Completed</p>
        </div>
        <div class="glass-card rounded-xl p-4 text-center border-l-4 border-red-500">
            <p class="text-2xl font-bold text-red-500">{{ $stats['emergency'] }}</p>
            <p class="text-xs text-muted">Emergency</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-2xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search requests..." class="input-field w-full pl-12 pr-4 py-3 rounded-xl">
            </div>
            <select wire:model.live="statusFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Statuses</option>
                <option value="new">New</option>
                <option value="assigned">Assigned</option>
                <option value="in_progress">In Progress</option>
                <option value="on_hold">On Hold</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select wire:model.live="priorityFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Priorities</option>
                <option value="emergency">🚨 Emergency</option>
                <option value="high">🔴 High</option>
                <option value="medium">🟡 Medium</option>
                <option value="low">🟢 Low</option>
            </select>
            <select wire:model.live="propertyFilter" class="input-field w-full px-4 py-3 rounded-xl">
                <option value="">All Properties</option>
                @foreach($properties as $property)
                <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Requests List -->
    @if($requests->count() > 0)
    <div class="space-y-4">
        @foreach($requests as $request)
        <div class="glass-card rounded-xl p-4 sm:p-5 hover:shadow-lg transition-all">
            <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                <div class="flex sm:flex-col items-center sm:items-start gap-3 sm:gap-2">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0
                        @if($request->priority === 'emergency') bg-red-500/15 text-red-500
                        @elseif($request->priority === 'high') bg-orange-500/15 text-orange-500
                        @elseif($request->priority === 'medium') bg-amber-500/15 text-amber-500
                        @else bg-green-500/15 text-green-500 @endif">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-wide
                        @if($request->priority === 'emergency') text-red-500
                        @elseif($request->priority === 'high') text-orange-500
                        @elseif($request->priority === 'medium') text-amber-500
                        @else text-green-500 @endif">
                        {{ $request->priority }}
                    </span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <a href="{{ route('maintenance.show', $request) }}" class="text-lg font-semibold text-primary hover:text-accent transition-colors">
                            {{ $request->title }}
                        </a>
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full
                            @if($request->status === 'new') bg-blue-500/15 text-blue-500
                            @elseif($request->status === 'assigned') bg-purple-500/15 text-purple-500
                            @elseif($request->status === 'in_progress') bg-amber-500/15 text-amber-500
                            @elseif($request->status === 'on_hold') bg-gray-500/15 text-gray-500
                            @elseif($request->status === 'completed') bg-green-500/15 text-green-500
                            @else bg-red-500/15 text-red-500 @endif">
                            {{ ucfirst(str_replace('_', ' ', $request->status)) }}
                        </span>
                        <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-input text-muted">
                            {{ ucfirst($request->category) }}
                        </span>
                    </div>
                    <p class="text-secondary text-sm mb-3 line-clamp-2">{{ $request->description }}</p>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-sm text-muted">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            {{ $request->unit->property->name }} - Unit {{ $request->unit->unit_number }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ $request->created_at->diffForHumans() }}
                        </span>
                        @if($request->vendor)
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            {{ $request->vendor->name }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:flex-shrink-0">
                    <a href="{{ route('maintenance.show', $request) }}" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">View</a>
                    <button wire:click="confirmDelete({{ $request->id }})" class="p-2 text-muted hover:text-red-500 hover:bg-red-500/10 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-6">{{ $requests->links() }}</div>
    @else
    <div class="glass-card rounded-2xl p-8 sm:p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-amber-500/15 flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
        </div>
        @if($search || $statusFilter || $priorityFilter || $propertyFilter)
        <h3 class="text-xl font-semibold text-primary mb-2">No requests found</h3>
        <p class="text-muted mb-6">Try adjusting your filters.</p>
        <button wire:click="$set('search', ''); $set('statusFilter', ''); $set('priorityFilter', ''); $set('propertyFilter', '')" class="btn-secondary px-5 py-2.5 rounded-xl text-sm font-medium">Clear Filters</button>
        @else
        <h3 class="text-xl font-semibold text-primary mb-2">No maintenance requests yet</h3>
        <p class="text-muted mb-6">Create your first maintenance request to start tracking repairs.</p>
        <a href="{{ route('maintenance.create') }}" class="btn-primary inline-flex items-center px-6 py-3 rounded-xl font-medium">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Create First Request
        </a>
        @endif
    </div>
    @endif

    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" wire:click="cancelDelete"></div>
        <div class="relative glass-card rounded-2xl p-6 w-full max-w-md animate-fade-in">
            <div class="w-14 h-14 rounded-full bg-red-500/15 text-red-500 flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-primary text-center mb-2">Delete Request?</h3>
            <p class="text-muted text-center mb-6">This action cannot be undone.</p>
            <div class="flex gap-3">
                <button wire:click="cancelDelete" class="flex-1 btn-secondary px-4 py-3 rounded-xl font-medium">Cancel</button>
                <button wire:click="deleteRequest" class="flex-1 px-4 py-3 bg-red-500 hover:bg-red-600 rounded-xl text-white font-medium transition-all">Delete</button>
            </div>
        </div>
    </div>
    @endif
</div>
