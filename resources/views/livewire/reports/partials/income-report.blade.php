<div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Rent</span>
                <span class="text-green-500 text-lg">💰</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($data['total_rent']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Late Fees</span>
                <span class="text-amber-500 text-lg">⚠️</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($data['total_late_fees']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Income</span>
                <span class="text-accent text-lg">📈</span>
            </div>
            <p class="text-2xl font-bold text-accent">${{ number_format($data['total_income']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Payments</span>
                <span class="text-blue-500 text-lg">📝</span>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $data['payment_count'] }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- By Month -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="p-4 border-b border-primary">
                <h3 class="text-lg font-semibold text-primary">Income by Month</h3>
            </div>
            @if($data['by_month']->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-primary">
                            <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Month</th>
                            <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Rent</th>
                            <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Late Fees</th>
                            <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($data['by_month'] as $month => $values)
                        <tr class="hover:bg-input/50">
                            <td class="px-4 py-3 text-sm text-primary font-medium">{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</td>
                            <td class="px-4 py-3 text-sm text-primary text-right">${{ number_format($values['rent']) }}</td>
                            <td class="px-4 py-3 text-sm text-amber-500 text-right">${{ number_format($values['late_fees']) }}</td>
                            <td class="px-4 py-3 text-sm text-primary text-right font-semibold">${{ number_format($values['total']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-t-2 border-primary">
                        <tr class="bg-input/30">
                            <td class="px-4 py-3 text-sm font-bold text-primary">Total</td>
                            <td class="px-4 py-3 text-sm font-bold text-primary text-right">${{ number_format($data['total_rent']) }}</td>
                            <td class="px-4 py-3 text-sm font-bold text-amber-500 text-right">${{ number_format($data['total_late_fees']) }}</td>
                            <td class="px-4 py-3 text-sm font-bold text-accent text-right">${{ number_format($data['total_income']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @else
            <div class="p-8 text-center">
                <p class="text-muted">No income data for this period.</p>
            </div>
            @endif
        </div>

        <!-- By Property -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="p-4 border-b border-primary">
                <h3 class="text-lg font-semibold text-primary">Income by Property</h3>
            </div>
            @if($data['by_property']->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-primary">
                            <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Property</th>
                            <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Rent</th>
                            <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Late Fees</th>
                            <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-primary">
                        @foreach($data['by_property'] as $property => $values)
                        <tr class="hover:bg-input/50">
                            <td class="px-4 py-3 text-sm text-primary font-medium">{{ $property }}</td>
                            <td class="px-4 py-3 text-sm text-primary text-right">${{ number_format($values['rent']) }}</td>
                            <td class="px-4 py-3 text-sm text-amber-500 text-right">${{ number_format($values['late_fees']) }}</td>
                            <td class="px-4 py-3 text-sm text-primary text-right font-semibold">${{ number_format($values['total']) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="p-8 text-center">
                <p class="text-muted">No income data for this period.</p>
            </div>
            @endif
        </div>
    </div>
</div>