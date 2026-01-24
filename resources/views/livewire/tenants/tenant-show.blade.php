<div>
    <a href="{{ route('tenants.index') }}" class="inline-flex items-center text-muted hover:text-primary mb-6 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Back to Tenants
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Tenant Header -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-4 mb-6">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-2xl font-bold text-white">{{ substr($tenant->first_name, 0, 1) }}{{ substr($tenant->last_name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-primary">{{ $tenant->full_name }}</h1>
                            <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-full
                                @if($tenant->status === 'active') bg-green-500/15 text-green-500 
                                @elseif($tenant->status === 'pending') bg-amber-500/15 text-amber-500 
                                @elseif($tenant->status === 'past') bg-gray-500/15 text-gray-500 
                                @else bg-red-500/15 text-red-500 @endif">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('tenants.edit', $tenant) }}" class="btn-secondary px-4 py-2 rounded-lg text-sm font-medium">Edit</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($tenant->phone)
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-3 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        <a href="tel:{{ $tenant->phone }}" class="text-secondary hover:text-accent">{{ $tenant->phone }}</a>
                    </div>
                    @endif
                    @if($tenant->email)
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-3 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <a href="mailto:{{ $tenant->email }}" class="text-secondary hover:text-accent">{{ $tenant->email }}</a>
                    </div>
                    @endif
                    @if($tenant->date_of_birth)
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-3 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-secondary">DOB: {{ $tenant->date_of_birth->format('M d, Y') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Leases -->
            <div class="glass-card rounded-2xl overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-primary flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-primary">Leases</h2>
                    <a href="{{ route('leases.create', ['tenant_id' => $tenant->id]) }}" class="btn-primary px-4 py-2 rounded-lg text-sm font-medium">
                        <svg class="w-4 h-4 mr-1.5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4" />
                        </svg>
                        New Lease
                    </a>
                </div>
                @if($tenant->leases->count() > 0)
                <div class="divide-y divide-primary">
                    @foreach($tenant->leases as $lease)
                    <div class="p-4 sm:p-6">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <h3 class="font-semibold text-primary">{{ $lease->unit->property->name }} - Unit {{ $lease->unit->unit_number }}</h3>
                                <p class="text-sm text-muted">{{ $lease->start_date->format('M d, Y') }} - {{ $lease->end_date->format('M d, Y') }}</p>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full
                                @if($lease->status === 'active') bg-green-500/15 text-green-500 
                                @elseif($lease->status === 'pending') bg-amber-500/15 text-amber-500 
                                @else bg-gray-500/15 text-gray-500 @endif">
                                {{ ucfirst($lease->status) }}
                            </span>
                        </div>
                        <p class="text-lg font-bold text-primary">${{ number_format($lease->rent_amount) }}<span class="text-sm font-normal text-muted">/mo</span></p>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="p-8 text-center">
                    <p class="text-muted">No leases found for this tenant.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Emergency Contact -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Emergency Contact</h3>
                @if($tenant->emergency_contact_name)
                <p class="font-medium text-primary">{{ $tenant->emergency_contact_name }}</p>
                @if($tenant->emergency_contact_phone)
                <a href="tel:{{ $tenant->emergency_contact_phone }}" class="text-sm text-secondary hover:text-accent">{{ $tenant->emergency_contact_phone }}</a>
                @endif
                @else
                <p class="text-muted text-sm">No emergency contact on file</p>
                @endif
            </div>

            <!-- Employment -->
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Employment</h3>
                @if($tenant->employer)
                <p class="font-medium text-primary">{{ $tenant->employer }}</p>
                @if($tenant->employer_phone)
                <p class="text-sm text-secondary">{{ $tenant->employer_phone }}</p>
                @endif
                @if($tenant->annual_income)
                <p class="text-sm text-muted mt-2">Annual Income: ${{ number_format($tenant->annual_income) }}</p>
                @endif
                @else
                <p class="text-muted text-sm">No employment info on file</p>
                @endif
            </div>

            <!-- Notes -->
            @if($tenant->notes)
            <div class="glass-card rounded-2xl p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-muted uppercase tracking-wide mb-4">Notes</h3>
                <p class="text-sm text-secondary">{{ $tenant->notes }}</p>
            </div>
            @endif
        </div>
    </div>
</div>