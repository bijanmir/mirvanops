<div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Units</span>
                <span class="text-blue-500 text-lg">🏢</span>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $data['totals']['total_units'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Occupied</span>
                <span class="text-green-500 text-lg">✅</span>
            </div>
            <p class="text-2xl font-bold text-green-500">{{ $data['totals']['total_occupied'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Vacant</span>
                <span class="text-amber-500 text-lg">🔑</span>
            </div>
            <p class="text-2xl font-bold text-amber-500">{{ $data['totals']['total_vacant'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Occupancy Rate</span>
                <span class="text-accent text-lg">📊</span>
            </div>
            <p class="text-2xl font-bold text-accent">{{ $data['totals']['overall_occupancy'] }}%</p>
        </div>
    </div>

    <!-- Financial Impact -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
        <div class="glass-card rounded-xl p-5">
            <p class="text-muted text-sm mb-1">Potential Monthly Rent</p>
            <p class="text-xl font-bold text-primary">${{ number_format($data['totals']['total_potential_rent']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <p class="text-muted text-sm mb-1">Actual Monthly Rent</p>
            <p class="text-xl font-bold text-green-500">${{ number_format($data['totals']['total_actual_rent']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <p class="text-muted text-sm mb-1">Vacancy Loss</p>
            <p class="text-xl font-bold text-red-500">${{ number_format($data['totals']['total_vacancy_loss']) }}</p>
        </div>
    </div>

    <!-- By Property Table -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="p-4 border-b border-primary">
            <h3 class="text-lg font-semibold text-primary">Occupancy by Property</h3>
        </div>
        @if($data['properties']->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-primary">
                        <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Property</th>
                        <th class="text-center text-xs font-semibold text-muted uppercase px-4 py-3">Total</th>
                        <th class="text-center text-xs font-semibold text-muted uppercase px-4 py-3">Occupied</th>
                        <th class="text-center text-xs font-semibold text-muted uppercase px-4 py-3">Vacant</th>
                        <th class="text-center text-xs font-semibold text-muted uppercase px-4 py-3">Maintenance</th>
                        <th class="text-center text-xs font-semibold text-muted uppercase px-4 py-3">Rate</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Vacancy Loss</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @foreach($data['properties'] as $item)
                    <tr class="hover:bg-input/50">
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-primary">{{ $item['property']->name }}</p>
                            <p class="text-xs text-muted">{{ $item['property']->address }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-primary text-center">{{ $item['total'] }}</td>
                        <td class="px-4 py-3 text-sm text-green-500 text-center font-medium">{{ $item['occupied'] }}</td>
                        <td class="px-4 py-3 text-sm text-amber-500 text-center font-medium">{{ $item['vacant'] }}</td>
                        <td class="px-4 py-3 text-sm text-blue-500 text-center">{{ $item['maintenance'] }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full
                                @if($item['occupancy_rate'] >= 90) bg-green-500/15 text-green-500
                                @elseif($item['occupancy_rate'] >= 70) bg-amber-500/15 text-amber-500
                                @else bg-red-500/15 text-red-500
                                @endif">
                                {{ $item['occupancy_rate'] }}%
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm text-red-500 text-right">${{ number_format($item['vacancy_loss']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center">
            <p class="text-muted">No properties found.</p>
        </div>
        @endif
    </div>
</div>