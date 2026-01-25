@extends('livewire.reports.pdf.layout')

@section('content')
<!-- Summary -->
<table class="summary-table">
    <tr>
        <td style="width: 25%;">
            <div class="summary-label">Monthly Rent</div>
            <div class="summary-value green">${{ number_format($data['total_rent']) }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Pet Rent</div>
            <div class="summary-value purple">${{ number_format($data['total_pet_rent']) }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Total Monthly</div>
            <div class="summary-value accent">${{ number_format($data['total_monthly']) }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Active Leases</div>
            <div class="summary-value blue">{{ $data['lease_count'] }}</div>
        </td>
    </tr>
</table>

<!-- Rent Roll Table -->
<div class="section">
    <div class="section-title">Active Leases</div>
    @if($data['leases']->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Property / Unit</th>
                <th>Tenant</th>
                <th>Lease Term</th>
                <th class="right">Rent</th>
                <th class="right">Pet Rent</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['leases'] as $lease)
            <tr>
                <td>
                    <strong>{{ $lease->unit->property->name ?? 'N/A' }}</strong><br>
                    <span class="text-muted text-small">Unit {{ $lease->unit->unit_number ?? 'N/A' }}</span>
                </td>
                <td>
                    {{ $lease->tenant->full_name ?? 'N/A' }}<br>
                    <span class="text-muted text-small">{{ $lease->tenant->email ?? '' }}</span>
                </td>
                <td>
                    {{ $lease->start_date->format('M d, Y') }}<br>
                    <span class="text-muted text-small">to {{ $lease->end_date->format('M d, Y') }}</span>
                </td>
                <td class="right">${{ number_format($lease->rent_amount) }}</td>
                <td class="right">
                    @if($lease->pet_rent)
                        ${{ number_format($lease->pet_rent) }}
                    @else
                        <span class="text-muted">-</span>
                    @endif
                </td>
                <td class="right bold">${{ number_format($lease->rent_amount + ($lease->pet_rent ?? 0)) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3" class="bold">Total ({{ $data['lease_count'] }} leases)</td>
                <td class="right">${{ number_format($data['total_rent']) }}</td>
                <td class="right">${{ number_format($data['total_pet_rent']) }}</td>
                <td class="right" style="color: #f59e0b;">${{ number_format($data['total_monthly']) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <p class="text-muted" style="text-align: center; padding: 20px;">No active leases found.</p>
    @endif
</div>
@endsection