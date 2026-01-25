<div>
    <!-- Summary Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Total Spent</span>
                <span class="text-red-500 text-lg">💸</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($data['total_cost']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Requests</span>
                <span class="text-blue-500 text-lg">🔧</span>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $data['request_count'] }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Average Cost</span>
                <span class="text-amber-500 text-lg">📊</span>
            </div>
            <p class="text-2xl font-bold text-primary">${{ number_format($data['avg_cost']) }}</p>
        </div>
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center justify-between mb-2">
                <span class="text-muted text-sm">Categories</span>
                <span class="text-purple-500 text-lg">📁</span>
            </div>
            <p class="text-2xl font-bold text-primary">{{ $data['by_category']->count() }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- By Category -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="p-4 border-b border-primary">
                <h3 class="text-lg font-semibold text-primary">Costs by Category</h3>
            </div>
            @if($data['by_category']->count() > 0)
            <div class="divide-y divide-primary">
                @foreach($data['by_category'] as $category => $values)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary">{{ $category ?: 'Uncategorized' }}</p>
                        <p class="text-xs text-muted">{{ $values['count'] }} requests • Avg ${{ number_format($values['avg_cost']) }}</p>
                    </div>
                    <p class="text-sm font-bold text-primary">${{ number_format($values['total_cost']) }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center">
                <p class="text-muted">No maintenance costs recorded.</p>
            </div>
            @endif
        </div>

        <!-- By Property -->
        <div class="glass-card rounded-xl overflow-hidden">
            <div class="p-4 border-b border-primary">
                <h3 class="text-lg font-semibold text-primary">Costs by Property</h3>
            </div>
            @if($data['by_property']->count() > 0)
            <div class="divide-y divide-primary">
                @foreach($data['by_property'] as $property => $values)
                <div class="p-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-primary">{{ $property }}</p>
                        <p class="text-xs text-muted">{{ $values['count'] }} requests</p>
                    </div>
                    <p class="text-sm font-bold text-primary">${{ number_format($values['total_cost']) }}</p>
                </div>
                @endforeach
            </div>
            @else
            <div class="p-8 text-center">
                <p class="text-muted">No maintenance costs recorded.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- By Month -->
    <div class="glass-card rounded-xl overflow-hidden mb-6">
        <div class="p-4 border-b border-primary">
            <h3 class="text-lg font-semibold text-primary">Costs by Month</h3>
        </div>
        @if($data['by_month']->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-primary">
                        <th class="text-left text-xs font-semibold text-muted uppercase px-4 py-3">Month</th>
                        <th class="text-center text-xs font-semibold text-muted uppercase px-4 py-3">Requests</th>
                        <th class="text-right text-xs font-semibold text-muted uppercase px-4 py-3">Total Cost</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-primary">
                    @foreach($data['by_month'] as $month => $values)
                    <tr class="hover:bg-input/50">
                        <td class="px-4 py-3 text-sm text-primary font-medium">{{ \Carbon\Carbon::parse($month . '-01')->format('M Y') }}</td>
                        <td class="px-4 py-3 text-sm text-primary text-center">{{ $values['count'] }}</td>
                        <td class="px-4 py-3 text-sm text-primary text-right font-semibold">${{ number_format($values['total_cost']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="p-8 text-center">
            <p class="text-muted">No maintenance costs recorded.</p>
        </div>
        @endif
    </div>

    <!-- Top Expenses -->
    @if($data['requests']->count() > 0)
    <div class="glass-card rounded-xl overflow-hidden">
        <div class="p-4 border-b border-primary">
            <h3 class="text-lg font-semibold text-primary">Top 10 Expenses</h3>
        </div>
        <div class="divide-y divide-primary">
            @foreach($data['requests'] as $request)
            <div class="p-4 flex items-center justify-between">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-primary truncate">{{ $request->title }}</p>
                    <p class="text-xs text-muted">{{ $request->property->name ?? 'N/A' }}@if($request->unit) • Unit {{ $request->unit->unit_number }}@endif • {{ $request->created_at->format('M d, Y') }}</p>
                </div>
                <p class="text-sm font-bold text-primary ml-4">${{ number_format($request->actual_cost) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>