@extends('livewire.reports.pdf.layout')

@section('content')
<div class="summary-row">
    <div class="summary-box">
        <div class="label">Total Rent</div>
        <div class="value green">${{ number_format($data['total_rent']) }}</div>
    </div>
    <div class="summary-box">
        <div class="label">Late Fees</div>
        <div class="value amber">${{ number_format($data['total_late_fees']) }}</div>
    </div>
    <div class="summary-box">
        <div class="label">Total Income</div>
        <div class="value accent">${{ number_format($data['total_income']) }}</div>
    </div>
    <div class="summary-box">
        <div class="label">Payments</div>
        <div class="value blue">{{ $data['payment_count'] }}</div>
    </div>
</div>

<div class="section">
    <div class="section-title">Income by Month</div>
    @if($data['by_month']->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Month</th>
                <th class="right">Rent Collected</th>
                <th class="right">Late Fees</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_month'] as $month => $values)
            <tr>
                <td class="bold">{{ \Carbon\Carbon::parse($month . '-01')->format('F Y') }}</td>
                <td class="right">${{ number_format($values['rent']) }}</td>
                <td class="right amber">${{ number_format($values['late_fees']) }}</td>
                <td class="right bold">${{ number_format($values['total']) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="right">${{ number_format($data['total_rent']) }}</td>
                <td class="right amber">${{ number_format($data['total_late_fees']) }}</td>
                <td class="right accent">${{ number_format($data['total_income']) }}</td>
            </tr>
        </tfoot>
    </table>
    @else
    <p style="color: #666; text-align: center; padding: 20px;">No income data for this period.</p>
    @endif
</div>

<div class="section">
    <div class="section-title">Income by Property</div>
    @if($data['by_property']->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Property</th>
                <th class="right">Rent Collected</th>
                <th class="right">Late Fees</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['by_property'] as $property => $values)
            <tr>
                <td class="bold">{{ $property }}</td>
                <td class="right">${{ number_format($values['rent']) }}</td>
                <td class="right amber">${{ number_format($values['late_fees']) }}</td>
                <td class="right bold">${{ number_format($values['total']) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p style="color: #666; text-align: center; padding: 20px;">No income data for this period.</p>
    @endif
</div>
@endsection