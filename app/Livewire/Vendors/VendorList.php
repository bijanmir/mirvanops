<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vendor;
use App\Models\ActivityLog;

class VendorList extends Component
{
    use WithPagination;

    public $search = '';
    public $specialtyFilter = '';
    public $statusFilter = '';
    public $showDeleteModal = false;
    public $vendorToDelete = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'specialtyFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($vendorId)
    {
        $this->vendorToDelete = $vendorId;
        $this->showDeleteModal = true;
    }

    public function deleteVendor()
    {
        $vendor = Vendor::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->vendorToDelete);
        
        ActivityLog::log('deleted', $vendor);
        $vendor->delete();

        $this->showDeleteModal = false;
        $this->vendorToDelete = null;

        session()->flash('success', 'Vendor deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->vendorToDelete = null;
    }

    public function toggleStatus($vendorId)
    {
        $vendor = Vendor::where('company_id', auth()->user()->company_id)
            ->findOrFail($vendorId);
        
        $vendor->update(['is_active' => !$vendor->is_active]);
        
        ActivityLog::log($vendor->is_active ? 'activated' : 'deactivated', $vendor);
        
        session()->flash('success', 'Vendor ' . ($vendor->is_active ? 'activated' : 'deactivated') . ' successfully.');
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $vendors = Vendor::where('company_id', $companyId)
            ->withCount(['maintenanceRequests', 'maintenanceRequests as open_requests_count' => function ($query) {
                $query->whereIn('status', ['new', 'assigned', 'in_progress']);
            }])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('phone', 'like', '%' . $this->search . '%')
                      ->orWhere('specialty', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->specialtyFilter, function ($query) {
                $query->where('specialty', $this->specialtyFilter);
            })
            ->when($this->statusFilter !== '', function ($query) {
                $query->where('is_active', $this->statusFilter === 'active');
            })
            ->orderBy('name')
            ->paginate(12);

        $specialties = Vendor::where('company_id', $companyId)
            ->distinct()
            ->pluck('specialty')
            ->filter()
            ->sort()
            ->values();

        $stats = [
            'total' => Vendor::where('company_id', $companyId)->count(),
            'active' => Vendor::where('company_id', $companyId)->where('is_active', true)->count(),
            'inactive' => Vendor::where('company_id', $companyId)->where('is_active', false)->count(),
        ];

        return view('livewire.vendors.vendor-list', [
            'vendors' => $vendors,
            'specialties' => $specialties,
            'stats' => $stats,
        ]);
    }
}