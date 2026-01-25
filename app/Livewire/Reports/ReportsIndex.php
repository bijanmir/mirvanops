<?php

namespace App\Livewire\Reports;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Lease;
use App\Models\Unit;
use App\Models\Property;
use App\Models\MaintenanceRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportsIndex extends Component
{
    public $activeReport = 'income';
    public $dateRange = 'this_year';
    public $startDate;
    public $endDate;
    public $propertyFilter = '';
    
    public function mount()
    {
        $this->setDateRange();
    }
    
    public function updatedDateRange()
    {
        $this->setDateRange();
    }
    
    public function setDateRange()
    {
        $now = now();
        
        switch ($this->dateRange) {
            case 'this_month':
                $this->startDate = $now->copy()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfMonth()->format('Y-m-d');
                break;
            case 'last_month':
                $this->startDate = $now->copy()->subMonth()->startOfMonth()->format('Y-m-d');
                $this->endDate = $now->copy()->subMonth()->endOfMonth()->format('Y-m-d');
                break;
            case 'this_quarter':
                $this->startDate = $now->copy()->startOfQuarter()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfQuarter()->format('Y-m-d');
                break;
            case 'this_year':
                $this->startDate = $now->copy()->startOfYear()->format('Y-m-d');
                $this->endDate = $now->copy()->endOfYear()->format('Y-m-d');
                break;
            case 'last_year':
                $this->startDate = $now->copy()->subYear()->startOfYear()->format('Y-m-d');
                $this->endDate = $now->copy()->subYear()->endOfYear()->format('Y-m-d');
                break;
            case 'custom':
                // Keep existing dates
                break;
        }
    }
    
    public function setReport($report)
    {
        $this->activeReport = $report;
    }
    
    public function getIncomeReportData()
    {
        $companyId = auth()->user()->company_id;
        
        $payments = Payment::where('company_id', $companyId)
            ->where('status', 'completed')
            ->whereBetween('payment_date', [$this->startDate, $this->endDate])
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('lease.unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->with(['lease.unit.property'])
            ->get();
        
        // Group by month
        $byMonth = $payments->groupBy(function ($payment) {
            return $payment->payment_date->format('Y-m');
        })->map(function ($group) {
            return [
                'rent' => $group->sum('amount'),
                'late_fees' => $group->sum('late_fee'),
                'total' => $group->sum('amount') + $group->sum('late_fee'),
                'count' => $group->count(),
            ];
        })->sortKeys();
        
        // Group by property
        $byProperty = $payments->groupBy(function ($payment) {
            return $payment->lease->unit->property->name ?? 'Unknown';
        })->map(function ($group) {
            return [
                'rent' => $group->sum('amount'),
                'late_fees' => $group->sum('late_fee'),
                'total' => $group->sum('amount') + $group->sum('late_fee'),
                'count' => $group->count(),
            ];
        })->sortKeys();
        
        return [
            'total_rent' => $payments->sum('amount'),
            'total_late_fees' => $payments->sum('late_fee'),
            'total_income' => $payments->sum('amount') + $payments->sum('late_fee'),
            'payment_count' => $payments->count(),
            'by_month' => $byMonth,
            'by_property' => $byProperty,
        ];
    }
    
    public function getRentRollData()
    {
        $companyId = auth()->user()->company_id;
        
        $leases = Lease::where('company_id', $companyId)
            ->where('status', 'active')
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->with(['tenant', 'unit.property'])
            ->get()
            ->sortBy(function ($lease) {
                return ($lease->unit->property->name ?? '') . '-' . ($lease->unit->unit_number ?? '');
            });
        
        $totalRent = $leases->sum('rent_amount');
        $totalPetRent = $leases->sum('pet_rent');
        
        return [
            'leases' => $leases,
            'total_rent' => $totalRent,
            'total_pet_rent' => $totalPetRent,
            'total_monthly' => $totalRent + $totalPetRent,
            'lease_count' => $leases->count(),
        ];
    }
    
    public function getOccupancyData()
    {
        $companyId = auth()->user()->company_id;
        
        $properties = Property::where('company_id', $companyId)
            ->when($this->propertyFilter, function ($query) {
                $query->where('id', $this->propertyFilter);
            })
            ->with(['units'])
            ->get();
        
        $data = $properties->map(function ($property) {
            $totalUnits = $property->units->count();
            $occupied = $property->units->where('status', 'occupied')->count();
            $vacant = $property->units->where('status', 'vacant')->count();
            $maintenance = $property->units->where('status', 'maintenance')->count();
            
            return [
                'property' => $property,
                'total' => $totalUnits,
                'occupied' => $occupied,
                'vacant' => $vacant,
                'maintenance' => $maintenance,
                'occupancy_rate' => $totalUnits > 0 ? round(($occupied / $totalUnits) * 100, 1) : 0,
                'potential_rent' => $property->units->sum('market_rent'),
                'actual_rent' => $property->units->where('status', 'occupied')->sum('market_rent'),
                'vacancy_loss' => $property->units->where('status', 'vacant')->sum('market_rent'),
            ];
        });
        
        $totals = [
            'total_units' => $data->sum('total'),
            'total_occupied' => $data->sum('occupied'),
            'total_vacant' => $data->sum('vacant'),
            'total_maintenance' => $data->sum('maintenance'),
            'overall_occupancy' => $data->sum('total') > 0 
                ? round(($data->sum('occupied') / $data->sum('total')) * 100, 1) 
                : 0,
            'total_potential_rent' => $data->sum('potential_rent'),
            'total_actual_rent' => $data->sum('actual_rent'),
            'total_vacancy_loss' => $data->sum('vacancy_loss'),
        ];
        
        return [
            'properties' => $data,
            'totals' => $totals,
        ];
    }
    
    public function getMaintenanceCostsData()
    {
        $companyId = auth()->user()->company_id;
        
        $requests = MaintenanceRequest::where('company_id', $companyId)
            ->whereNotNull('actual_cost')
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->with(['property', 'unit', 'vendor'])
            ->get();
        
        // By category
        $byCategory = $requests->groupBy('category')->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_cost' => $group->sum('actual_cost'),
                'avg_cost' => $group->count() > 0 ? round($group->sum('actual_cost') / $group->count(), 2) : 0,
            ];
        })->sortByDesc('total_cost');
        
        // By property
        $byProperty = $requests->groupBy(function ($request) {
            return $request->property->name ?? 'Unknown';
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_cost' => $group->sum('actual_cost'),
            ];
        })->sortByDesc('total_cost');
        
        // By month
        $byMonth = $requests->groupBy(function ($request) {
            return $request->created_at->format('Y-m');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'total_cost' => $group->sum('actual_cost'),
            ];
        })->sortKeys();
        
        return [
            'total_cost' => $requests->sum('actual_cost'),
            'request_count' => $requests->count(),
            'avg_cost' => $requests->count() > 0 ? round($requests->sum('actual_cost') / $requests->count(), 2) : 0,
            'by_category' => $byCategory,
            'by_property' => $byProperty,
            'by_month' => $byMonth,
            'requests' => $requests->sortByDesc('actual_cost')->take(10),
        ];
    }
    
    public function render()
    {
        $companyId = auth()->user()->company_id;
        $properties = Property::where('company_id', $companyId)->orderBy('name')->get();
        
        $reportData = match($this->activeReport) {
            'income' => $this->getIncomeReportData(),
            'rent_roll' => $this->getRentRollData(),
            'occupancy' => $this->getOccupancyData(),
            'maintenance' => $this->getMaintenanceCostsData(),
            default => [],
        };
        
        return view('livewire.reports.reports-index', [
            'properties' => $properties,
            'reportData' => $reportData,
        ]);
    }
    public function exportPdf()
{
    $reportData = match($this->activeReport) {
        'income' => $this->getIncomeReportData(),
        'rent_roll' => $this->getRentRollData(),
        'occupancy' => $this->getOccupancyData(),
        'maintenance' => $this->getMaintenanceCostsData(),
        default => [],
    };
    
    $company = auth()->user()->company;
    $reportTitle = match($this->activeReport) {
        'income' => 'Income Report',
        'rent_roll' => 'Rent Roll',
        'occupancy' => 'Occupancy Report',
        'maintenance' => 'Maintenance Costs Report',
        default => 'Report',
    };
    
    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('livewire.reports.pdf.' . $this->activeReport, [
        'data' => $reportData,
        'company' => $company,
        'reportTitle' => $reportTitle,
        'startDate' => $this->startDate,
        'endDate' => $this->endDate,
        'generatedAt' => now(),
    ]);
    
    $filename = strtolower(str_replace(' ', '-', $reportTitle)) . '-' . now()->format('Y-m-d') . '.pdf';
    
    return response()->streamDownload(function () use ($pdf) {
        echo $pdf->output();
    }, $filename);
}
}