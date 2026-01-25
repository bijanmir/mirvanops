@extends('livewire.reports.pdf.layout')

@section('content')
<!-- Summary -->
<table class="summary-table">
    <tr>
        <td style="width: 25%;">
            <div class="summary-label">Total Units</div>
            <div class="summary-value blue">{{ $data['totals']['total_units'] }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Occupied</div>
            <div class="summary-value green">{{ $data['totals']['total_occupied'] }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Vacant</div>
            <div class="summary-value amber">{{ $data['totals']['total_vacant'] }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Occupancy Rate</div>
            <div class="summary-value accent">{{ $data['totals']['overall_occupancy'] }}%</div>
        </td>
    </tr>
</table>

<!-- Financial Impact -->
<table class="summary-table">
    <tr>
        <td style="width: 33%;">
            <div class="summary-label">Potential Monthly Rent</div>
            <div class="summary-value">${{ number_format($data['totals']['total_potential_rent']) }}</div>
        </td>
        <td style="width: 33%;">
            <div class="summary-label">Actual Monthly Rent</div>
            <div class="summary-value green">${{ number_format($data['totals']['total_actual_rent']) }}</div>
        </td>
        <td style="width: 33%;">
            <div class="summary-label">Vacancy Loss</div>
            <div class="summary-value red">${{ number_format($data['totals']['total_vacancy_loss']) }}</div>
        </td>
    </tr>
</table>

<!-- Occupancy by Property -->
<div class="section">
    <div class="section-title">Occupancy by Property</div>
    @if($data['properties']->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Property</th>
                <th class="center">Total</th>
                <th class="center">Occupied</th>
                <th class="center">Vacant</th>
                <th class="center">Maint.</th>
                <th class="center">Rate</th>
                <th class="right">Vacancy Loss</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['properties'] as $item)
            <tr>
                <td>
                    <strong>{{ $item['property']->name }}</strong><br>
                    <span class="text-muted text-small">{{ $item['property']->address }}</span>
                </td>
                <td class="center">{{ $item['total'] }}</td>
                <td class="center green bold">{{ $item['occupied'] }}</td>
                <td class="center amber bold">{{ $item['vacant'] }}</td>
                <td class="center">{{ $item['maintenance'] }}</td>
                <td class="center">
                    <span class="badge @if($item['occupancy_rate'] >= 90) badge-green @elseif($item['occupancy_rate'] >= 70) badge-amber @else badge-red @endif">
                        {{ $item['occupancy_rate'] }}%
                    </span>
                </td>
                <td class="right red">${{ number_format($item['vacancy_loss']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p class="text-muted" style="text-align: center; padding: 20px;">No properties found.</p>
    @endif
</div>
@endsection