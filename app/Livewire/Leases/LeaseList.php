<?php

namespace App\Livewire\Leases;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Lease;
use App\Models\Property;
use App\Models\ActivityLog;

class LeaseList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $propertyFilter = '';
    public $showDeleteModal = false;
    public $leaseToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($leaseId)
    {
        $this->leaseToDelete = $leaseId;
        $this->showDeleteModal = true;
    }

    public function deleteLease()
    {
        $lease = Lease::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->leaseToDelete);
        
        $lease->unit->update(['status' => 'vacant']);
        ActivityLog::log('deleted', $lease);
        $lease->delete();

        $this->showDeleteModal = false;
        $this->leaseToDelete = null;
        session()->flash('success', 'Lease deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->leaseToDelete = null;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $leases = Lease::where('company_id', $companyId)
            ->with(['tenant', 'unit.property'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('tenant', function ($tq) {
                        $tq->where('first_name', 'like', '%' . $this->search . '%')
                           ->orWhere('last_name', 'like', '%' . $this->search . '%');
                    });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->latest()
            ->paginate(12);

        $properties = Property::where('company_id', $companyId)->orderBy('name')->get();

        $stats = [
            'total' => Lease::where('company_id', $companyId)->count(),
            'active' => Lease::where('company_id', $companyId)->where('status', 'active')->count(),
            'pending' => Lease::where('company_id', $companyId)->where('status', 'pending')->count(),
            'expiring_soon' => Lease::where('company_id', $companyId)
                ->where('status', 'active')
                ->whereBetween('end_date', [now(), now()->addDays(30)])
                ->count(),
            'monthly_revenue' => Lease::where('company_id', $companyId)->where('status', 'active')->sum('rent_amount'),
        ];

        return view('livewire.leases.lease-list', [
            'leases' => $leases,
            'properties' => $properties,
            'stats' => $stats,
        ]);
    }
}
