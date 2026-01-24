<x-app-layout>
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Welcome back, {{ explode(' ', auth()->user()->name)[0] }} 👋</h1>
        <p class="text-white/60">Here's what's happening with your properties today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Properties -->
        <div class="glass-card stat-card rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/60 text-sm font-medium">Total Properties</p>
                    <p class="text-4xl font-bold text-white mt-2">{{ auth()->user()->company->properties()->count() }}</p>
                    <p class="text-emerald-400 text-sm mt-2 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        Active
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500/20 to-blue-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Units -->
        <div class="glass-card stat-card rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/60 text-sm font-medium">Total Units</p>
                    <p class="text-4xl font-bold text-white mt-2">{{ auth()->user()->company->units()->count() }}</p>
                    @php
                        $occupiedUnits = auth()->user()->company->units()->where('status', 'occupied')->count();
                        $totalUnits = auth()->user()->company->units()->count();
                        $occupancyRate = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0;
                    @endphp
                    <p class="text-white/40 text-sm mt-2">{{ $occupancyRate }}% occupied</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500/20 to-emerald-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Tenants -->
        <div class="glass-card stat-card rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/60 text-sm font-medium">Active Tenants</p>
                    <p class="text-4xl font-bold text-white mt-2">{{ auth()->user()->company->tenants()->count() }}</p>
                    <p class="text-white/40 text-sm mt-2">Across all properties</p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-purple-500/20 to-purple-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Open Requests -->
        <div class="glass-card stat-card rounded-2xl p-6">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-white/60 text-sm font-medium">Open Requests</p>
                    @php
                        $openRequests = auth()->user()->company->maintenanceRequests()->whereIn('status', ['new', 'assigned', 'in_progress'])->count();
                    @endphp
                    <p class="text-4xl font-bold text-white mt-2">{{ $openRequests }}</p>
                    <p class="{{ $openRequests > 0 ? 'text-amber-400' : 'text-emerald-400' }} text-sm mt-2 flex items-center">
                        @if($openRequests > 0)
                        <span class="w-2 h-2 rounded-full bg-amber-400 mr-2 animate-pulse"></span>
                        Needs attention
                        @else
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        All clear
                        @endif
                    </p>
                </div>
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center">
                    <svg class="w-7 h-7 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Quick Actions -->
        <div class="lg:col-span-2 glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Quick Actions
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('properties.create') }}" class="group p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-amber-500/50 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="text-white font-medium mb-1">Add Property</h3>
                    <p class="text-white/50 text-sm">Register a new property</p>
                </a>

                <a href="{{ route('tenants.create') }}" class="group p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-purple-500/50 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-medium mb-1">Add Tenant</h3>
                    <p class="text-white/50 text-sm">Register a new tenant</p>
                </a>

                <a href="{{ route('maintenance.create') }}" class="group p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-blue-500/50 transition-all duration-300">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <h3 class="text-white font-medium mb-1">New Request</h3>
                    <p class="text-white/50 text-sm">Create maintenance request</p>
                </a>
            </div>
        </div>

        <!-- Activity Feed -->
        <div class="glass-card rounded-2xl p-6">
            <h2 class="text-lg font-semibold text-white mb-6 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Recent Activity
            </h2>
            @php
                $recentLogs = auth()->user()->company->activityLogs()->with('user')->latest()->take(5)->get();
            @endphp
            @if($recentLogs->count() > 0)
            <div class="space-y-4">
                @foreach($recentLogs as $log)
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center flex-shrink-0">
                        <span class="text-xs font-medium text-white/70">{{ substr($log->user?->name ?? 'S', 0, 1) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-white/80">
                            <span class="font-medium">{{ $log->user?->name ?? 'System' }}</span>
                            {{ $log->action }} {{ strtolower($log->model_type) }}
                        </p>
                        <p class="text-xs text-white/40">{{ $log->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-white/40 text-sm">No recent activity</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Empty State (show only if no properties) -->
    @if(auth()->user()->company->properties()->count() === 0)
    <div class="glass-card rounded-2xl p-12 text-center">
        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-amber-500/20 to-amber-600/20 flex items-center justify-center mx-auto mb-6 animate-float">
            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-white mb-2">No properties yet</h3>
        <p class="text-white/60 mb-8 max-w-md mx-auto">Get started by adding your first property. Once added, you can manage units, tenants, and maintenance requests.</p>
        <a href="{{ route('properties.create') }}" class="btn-primary inline-flex items-center px-6 py-3 text-white font-medium rounded-xl">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
            </svg>
            Add Your First Property
        </a>
    </div>
    @endif
</x-app-layout>