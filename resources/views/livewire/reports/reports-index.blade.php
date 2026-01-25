<div>
    <!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-primary">Reports</h1>
        <p class="text-muted mt-1">Financial and operational insights</p>
    </div>
    <button wire:click="exportPdf" wire:loading.attr="disabled" class="btn-primary inline-flex items-center justify-center px-6 py-3 rounded-xl font-medium">
    <span wire:loading.remove wire:target="exportPdf" class="inline-flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Export PDF
    </span>
    <span wire:loading wire:target="exportPdf" class="inline-flex items-center">
        <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Generating...
    </span>
</button>
</div>

    <!-- Report Tabs -->
    <div class="glass-card rounded-xl p-1 mb-6 inline-flex flex-wrap gap-1">
        <button wire:click="setReport('income')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeReport === 'income' ? 'bg-black text-white' : 'text-secondary hover:text-primary hover:bg-input' }}">
            💰 Income Report
        </button>
        <button wire:click="setReport('rent_roll')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeReport === 'rent_roll' ? 'bg-black text-white' : 'text-secondary hover:text-primary hover:bg-input' }}">
            📋 Rent Roll
        </button>
        <button wire:click="setReport('occupancy')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeReport === 'occupancy' ? 'bg-black text-white' : 'text-secondary hover:text-primary hover:bg-input' }}">
            🏠 Occupancy
        </button>
        <button wire:click="setReport('maintenance')" class="px-4 py-2 rounded-lg text-sm font-medium transition-all {{ $activeReport === 'maintenance' ? 'bg-black text-white' : 'text-secondary hover:text-primary hover:bg-input' }}">
            🔧 Maintenance Costs
        </button>
    </div>

    <!-- Filters -->
    <div class="glass-card rounded-xl p-4 mb-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            @if($activeReport !== 'rent_roll' && $activeReport !== 'occupancy')
            <div>
                <label class="block text-xs font-medium text-muted mb-1">Date Range</label>
                <select wire:model.live="dateRange" class="input-field w-full px-4 py-2.5 rounded-xl text-sm">
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_quarter">This Quarter</option>
                    <option value="this_year">This Year</option>
                    <option value="last_year">Last Year</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            @endif
            
            @if($dateRange === 'custom' && $activeReport !== 'rent_roll' && $activeReport !== 'occupancy')
            <div>
                <label class="block text-xs font-medium text-muted mb-1">Start Date</label>
                <input wire:model.live="startDate" type="date" class="input-field w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-muted mb-1">End Date</label>
                <input wire:model.live="endDate" type="date" class="input-field w-full px-4 py-2.5 rounded-xl text-sm">
            </div>
            @endif
            
            <div>
                <label class="block text-xs font-medium text-muted mb-1">Property</label>
                <select wire:model.live="propertyFilter" class="input-field w-full px-4 py-2.5 rounded-xl text-sm">
                    <option value="">All Properties</option>
                    @foreach($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Report Content -->
    @if($activeReport === 'income')
        @include('livewire.reports.partials.income-report', ['data' => $reportData])
    @elseif($activeReport === 'rent_roll')
        @include('livewire.reports.partials.rent-roll', ['data' => $reportData])
    @elseif($activeReport === 'occupancy')
        @include('livewire.reports.partials.occupancy-report', ['data' => $reportData])
    @elseif($activeReport === 'maintenance')
        @include('livewire.reports.partials.maintenance-report', ['data' => $reportData])
    @endif
</div>