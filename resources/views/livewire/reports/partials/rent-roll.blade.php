<div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Monthly Rent</span>
                <span class="text-green-500 text-lg">💰</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($data['total_rent']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Pet Rent</span>
                <span class="text-purple-500 text-lg">🐾</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($data['total_pet_rent']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Monthly</span>
                <span class="text-accent text-lg">📈</span>
            </div>
            <p class="text-2xl font-bold text-accent">${{ number_format($data['total_monthly']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Active Leases</span>
                <span class="text-blue-500 text-lg">📋</span>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $data['lease_count'] }}</p>
        </div>
    </div>

    <!-- Rent Roll Table -->
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="p-4 border-b border-primary">
            <h3 class="text-lg font-semibold text-primary">Active Leases</h3>
        </div>
        @if($data['leases']->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-primary">
                        <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Property / Unit</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Tenant</th>
                        <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Lease Term</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Rent</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Pet Rent</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @foreach($data['leases'] as $lease)
                    <tr class="hover:bg-input/50">
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-primary">{{ $lease->unit->property->name ?? 'N/A' }}</p>
                            <p class="text-xs text-muted">Unit {{ $lease->unit->unit_number ?? 'N/A' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-primary">{{ $lease->tenant->full_name ?? 'N/A' }}</p>
                            <p class="text-xs text-muted">{{ $lease->tenant->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-primary">{{ $lease->start_date->format('M d, Y') }}</p>
                            <p class="text-xs text-muted">to {{ $lease->end_date->format('M d, Y') }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-primary text-right">${{ number_format($lease->rent_amount) }}</td>
                        <td class="px-4 py-3 text-sm text-primary text-right">
                            @if($lease->pet_rent)
                                ${{ number_format($lease->pet_rent) }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-primary text-right font-semibold">${{ number_format($lease->rent_amount + ($lease->pet_rent ?? 0)) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-primary">
                    <tr class="bg-input/30">
                        <td colspan="3" class="px-4 py-3 text-sm font-bold text-primary">Total ({{ $data['lease_count'] }} leases)</td>
                        <td class="px-4 py-3 text-sm font-bold text-primary text-right">${{ number_format($data['total_rent']) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-primary text-right">${{ number_format($data['total_pet_rent']) }}</td>
                        <td class="px-4 py-3 text-sm font-bold text-accent text-right">${{ number_format($data['total_monthly']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="p-8 text-center">
            <p class="text-muted">No active leases found.</p>
        </div>
        @endif
    </div>
</div>