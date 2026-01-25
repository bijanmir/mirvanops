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
use Illuminate\Support\Facades\Cache;

class Dashboard extends Component
{
    public function render()
    {
        $companyId = auth()->user()->company_id;
        $cacheKey = "dashboard_data_{$companyId}";
        
        // Cache dashboard data for 5 minutes
        $data = Cache::remember($cacheKey, 300, function () use ($companyId) {
            return $this->getDashboardData($companyId);
        });
        
        return view('livewire.dashboard', $data);
    }
    
    protected function getDashboardData($companyId)
    {
        $now = now();
        $currentMonth = $now->format('F Y');
        $monthStart = $now->copy()->startOfMonth();
        $monthEnd = $now->copy()->endOfMonth();

        // Basic counts - single queries
        $stats = [
            'properties' => Property::where('company_id', $companyId)->count(),
            'units' => Unit::where('company_id', $companyId)->count(),
            'tenants' => Tenant::where('company_id', $companyId)->where('status', 'active')->count(),
            'active_leases' => Lease::where('company_id', $companyId)->where('status', 'active')->count(),
        ];

        // Occupancy
        $unitCounts = Unit::where('company_id', $companyId)
            ->selectRaw("status, COUNT(*) as count")
            ->groupBy('status')
            ->pluck('count', 'status');
        
        $occupiedUnits = $unitCounts['occupied'] ?? 0;
        $stats['vacant_units'] = $unitCounts['vacant'] ?? 0;
        $stats['occupancy_rate'] = $stats['units'] > 0 ? round(($occupiedUnits / $stats['units']) * 100, 1) : 0;

        // Financial - single query for payments
        $paymentStats = Payment::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$monthStart, $monthEnd])
            ->selectRaw('SUM(amount) as total_amount, SUM(late_fee) as total_late_fees')
            ->first();

        $expectedRent = Lease::where('company_id', $companyId)
            ->where('status', 'active')
            ->sum('rent_amount');

        $collectedThisMonth = $paymentStats->total_amount ?? 0;
        $lateFees = $paymentStats->total_late_fees ?? 0;

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
            ->whereIn('priority', ['emergency', 'high'])
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

        return [
            'stats' => $stats,
            'financial' => $financial,
            'paymentsDue' => $paymentsDue,
            'expiringLeases' => $expiringLeases,
            'recentPayments' => $recentPayments,
            'maintenanceAlerts' => $maintenanceAlerts,
            'openMaintenance' => $openMaintenance,
            'recentActivity' => $recentActivity,
            'currentMonth' => $currentMonth,
        ];
    }
    
    // Call this method to refresh dashboard cache when data changes
    public static function clearCache($companyId)
    {
        Cache::forget("dashboard_data_{$companyId}");
    }
}