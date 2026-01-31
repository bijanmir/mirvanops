<?php

namespace App\Livewire\Tenants;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\ActivityLog;

class TenantList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $propertyFilter = '';
    public $showDeleteModal = false;
    public $tenantToDelete = null;
    public $tenantToDeleteName = '';
    public $tenantToDeleteHasActiveLease = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'propertyFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($tenantId)
    {
        $tenant = Tenant::where('company_id', auth()->user()->company_id)
            ->withCount(['leases' => function ($query) {
                $query->whereIn('status', ['active', 'pending']);
            }])
            ->findOrFail($tenantId);

        $this->tenantToDelete = $tenantId;
        $this->tenantToDeleteName = $tenant->first_name . ' ' . $tenant->last_name;
        $this->tenantToDeleteHasActiveLease = $tenant->leases_count > 0;
        $this->showDeleteModal = true;
    }

    public function deleteTenant()
    {
        $tenant = Tenant::where('company_id', auth()->user()->company_id)
            ->withCount(['leases' => function ($query) {
                $query->whereIn('status', ['active', 'pending']);
            }])
            ->findOrFail($this->tenantToDelete);

        // Block deletion if tenant has active leases
        if ($tenant->leases_count > 0) {
            session()->flash('error', 'Cannot delete tenant with active leases. Please terminate the leases first.');
            $this->showDeleteModal = false;
            $this->tenantToDelete = null;
            return;
        }

        ActivityLog::log('deleted', $tenant);
        $tenant->delete();

        $this->showDeleteModal = false;
        $this->tenantToDelete = null;
        $this->tenantToDeleteName = '';
        $this->tenantToDeleteHasActiveLease = false;

        session()->flash('success', 'Tenant deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->tenantToDelete = null;
        $this->tenantToDeleteName = '';
        $this->tenantToDeleteHasActiveLease = false;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $tenants = Tenant::where('company_id', $companyId)
            ->with(['leases' => function ($query) {
                $query->where('status', 'active')->with('unit.property');
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('leases.unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->paginate(12);

        $properties = Property::where('company_id', $companyId)->orderBy('name')->get();

        $stats = [
            'total' => Tenant::where('company_id', $companyId)->count(),
            'active' => Tenant::where('company_id', $companyId)->where('status', 'active')->count(),
            'past' => Tenant::where('company_id', $companyId)->where('status', 'past')->count(),
            'pending' => Tenant::where('company_id', $companyId)->where('status', 'pending')->count(),
        ];

        return view('livewire.tenants.tenant-list', [
            'tenants' => $tenants,
            'properties' => $properties,
            'stats' => $stats,
        ]);
    }
}
