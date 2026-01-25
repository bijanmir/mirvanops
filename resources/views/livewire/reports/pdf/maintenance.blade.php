@extends('livewire.reports.pdf.layout')

@section('content')
<!-- Summary -->
<table class="summary-table">
    <tr>
        <td style="width: 25%;">
            <div class="summary-label">Total Spent</div>
            <div class="summary-value red">${{ number_format($data['total_cost']) }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Requests</div>
            <div class="summary-value blue">{{ $data['request_count'] }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Average Cost</div>
            <div class="summary-value amber">${{ number_format($data['avg_cost']) }}</div>
        </td>
        <td style="width: 25%;">
            <div class="summary-label">Categories</div>
            <div class="summary-value purple">{{ $data['by_category']->count() }}</div>
        </td>
    </tr>
</table>

<!-- Costs by Category -->
<div class="section">
    <div class="section-title">Costs by Category</div>
    @if($data['by_category']->count() > 0)
    <table class="data-table">
        <thead>
            <tr>
                <th>Category</th>
                <th class="center">Requests</th>
                <th class="right">Average Cost</th>
                <th class="right">Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_category'] as $category => $values)
            <tr>
                <td class="bold">{{ $category ?: 'Uncategorized' }}</td>
                <td class="center">{{ $values['count'] }}</td>
                <td class="right">${{ number_format($values['avg_cost']) }}</td>
                <td class="right bold">${{ number_format($values['total_cost']) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td class="bold">Total</td>
                <td class="center">{{ $data['request_count'] }}</td>
                <td class="right">${{ number_format($data['avg_cost']) }}</td>
                <td class="right red">${{ number_format($data['total_cost']) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <p class="text-muted" style="text-align: center; padding: 20px;">No maintenance costs recorded.</p>
    @endif
</div>

<!-- Costs by Property -->
@if($data['by_property']->count() > 0)
<div class="section">
    <div class="section-title">Costs by Property</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Property</th>
                <th class="center">Requests</th>
                <th class="right">Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_property'] as $property => $values)
            <tr>
                <td class="bold">{{ $property }}</td>
                <td class="center">{{ $values['count'] }}</td>
                <td class="right bold">${{ number_format($values['total_cost']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Costs by Month -->
@if($data['by_month']->count() > 0)
<div class="section">
    <div class="section-title">Costs by Month</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Month</th>
                <th class="center">Requests</th>
                <th class="right">Total Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_month'] as $month => $values)
            <tr>
                <td class="bold">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</td>
                <td class="center">{{ $values['count'] }}</td>
                <td class="right bold">${{ number_format($values['total_cost']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<!-- Top Expenses -->
@if($data['requests']->count() > 0)
<div class="section">
    <div class="section-title">Top 10 Expenses</div>
    <table class="data-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Property</th>
                <th>Date</th>
                <th class="right">Cost</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['requests'] as $request)
            <tr>
                <td class="bold">{{ $request->title }}</td>
                <td>{{ $request->property->name ?? 'N/A' }}@if($request->unit) - Unit {{ $request->unit->unit_number }}@endif</td>
                <td>{{ $request->created_at->format('M d, Y') }}</td>
                <td class="right bold">${{ number_format($request->actual_cost) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection