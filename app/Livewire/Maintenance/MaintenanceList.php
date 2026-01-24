<?php

namespace App\Livewire\Maintenance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\ActivityLog;

class MaintenanceList extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    public $priorityFilter = '';
    public $propertyFilter = '';
    public $showDeleteModal = false;
    public $requestToDelete = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function confirmDelete($requestId)
    {
        $this->requestToDelete = $requestId;
        $this->showDeleteModal = true;
    }

    public function deleteRequest()
    {
        $request = MaintenanceRequest::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->requestToDelete);
        
        ActivityLog::log('deleted', $request);
        $request->delete();
        
        $this->showDeleteModal = false;
        $this->requestToDelete = null;
        
        session()->flash('success', 'Maintenance request deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->requestToDelete = null;
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        $requests = MaintenanceRequest::where('company_id', $companyId)
            ->with(['property', 'unit', 'vendor', 'reportedBy'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->priorityFilter, fn($q) => $q->where('priority', $this->priorityFilter))
            ->when($this->propertyFilter, fn($q) => $q->where('property_id', $this->propertyFilter))
            ->latest()
            ->paginate(10);

        $stats = [
            'total' => MaintenanceRequest::where('company_id', $companyId)->count(),
            'new' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'new')->count(),
            'assigned' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'assigned')->count(),
            'in_progress' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'in_progress')->count(),
            'on_hold' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'on_hold')->count(),
            'completed' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'completed')->count(),
        ];

        $properties = Property::where('company_id', $companyId)->orderBy('name')->get();

        return view('livewire.maintenance.maintenance-list', [
            'requests' => $requests,
            'stats' => $stats,
            'properties' => $properties,
        ]);
    }
}
