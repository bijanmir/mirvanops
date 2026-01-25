<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\Payment;
use App\Models\MaintenanceRequest;
use App\Models\ActivityLog;
use Carbon\Carbon;

class Dashboard extends Component
{
    public function render()
    {
        $companyId = auth()->user()->company_id;
        $now = now();
        $currentMonth = $now->format('F Y');

        // Basic counts
        $stats = [
            'properties' => Property::where('company_id', $companyId)->count(),
            'units' => Unit::where('company_id', $companyId)->count(),
            'tenants' => Tenant::where('company_id', $companyId)->where('status', 'active')->count(),
            'active_leases' => Lease::where('company_id', $companyId)->where('status', 'active')->count(),
        ];

        // Occupancy
        $occupiedUnits = Unit::where('company_id', $companyId)->where('status', 'occupied')->count();
        $totalUnits = $stats['units'];
        $stats['occupancy_rate'] = $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 1) : 0;
        $stats['vacant_units'] = Unit::where('company_id', $companyId)->where('status', 'vacant')->count();

        // Financial - This Month
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        $collectedThisMonth = Payment::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->sum('amount');

        $expectedRent = Lease::where('company_id', $companyId)
            ->where('status', 'active')
            ->sum('rent_amount');

        $lateFees = Payment::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->sum('late_fee');

        $financial = [
            'collected' => $collectedThisMonth,
            'expected' => $expectedRent,
            'late_fees' => $lateFees,
            'collection_rate' => $expectedRent > 0 ? round(($collectedThisMonth / $expectedRent) * 100, 1) : 0,
            'outstanding' => max(0, $expectedRent - $collectedThisMonth),
        ];

        // Payments due (active leases without payment this month)
        $paidLeaseIds = Payment::where('company_id', $companyId)
            ->where('status', 'completed')
            ->where('period_covered', $currentMonth)
            ->pluck('lease_id');

        $paymentsDue = Lease::where('company_id', $companyId)
            ->where('status', 'active')
            ->whereNotIn('id', $paidLeaseIds)
            ->with(['tenant', 'unit.property'])
            ->get();

        // Leases expiring in next 60 days
        $expiringLeases = Lease::where('company_id', $companyId)
            ->where('status', 'active')
            ->whereBetween('end_date', [$now, $now->copy()->addDays(60)])
            ->with(['tenant', 'unit.property'])
            ->orderBy('end_date')
            ->limit(5)
            ->get();

        // Recent payments
        $recentPayments = Payment::where('company_id', $companyId)
            ->with(['tenant', 'lease.unit.property'])
            ->latest('payment_date')
            ->limit(5)
            ->get();

        // Maintenance requests needing attention
        $maintenanceAlerts = MaintenanceRequest::where('company_id', $companyId)
            ->whereIn('status', ['new', 'in_progress'])
            ->where(function ($q) {
                $q->where('priority', 'emergency')
                  ->orWhere('priority', 'high');
            })
            ->with(['property', 'unit'])
            ->latest()
            ->limit(5)
            ->get();

        // Open maintenance count
        $openMaintenance = MaintenanceRequest::where('company_id', $companyId)
            ->whereIn('status', ['new', 'assigned', 'in_progress'])
            ->count();

        // Recent activity
        $recentActivity = ActivityLog::where('company_id', $companyId)
            ->with('user')
            ->latest()
            ->limit(8)
            ->get();

        return view('livewire.dashboard', [
            'stats' => $stats,
            'financial' => $financial,
            'paymentsDue' => $paymentsDue,
            'expiringLeases' => $expiringLeases,
            'recentPayments' => $recentPayments,
            'maintenanceAlerts' => $maintenanceAlerts,
            'openMaintenance' => $openMaintenance,
            'recentActivity' => $recentActivity,
            'currentMonth' => $currentMonth,
        ]);
    }
}