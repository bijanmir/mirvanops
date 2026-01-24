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

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'priorityFilter' => ['except' => ''],
        'propertyFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPriorityFilter()
    {
        $this->resetPage();
    }

    public function updatingPropertyFilter()
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
            ->with(['unit.property', 'assignedTo'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('title', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('unit', function ($uq) {
                          $uq->where('unit_number', 'like', '%' . $this->search . '%');
                      })
                      ->orWhereHas('unit.property', function ($pq) {
                          $pq->where('name', 'like', '%' . $this->search . '%');
                      });
                });
            })
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->priorityFilter, function ($query) {
                $query->where('priority', $this->priorityFilter);
            })
            ->when($this->propertyFilter, function ($query) {
                $query->whereHas('unit', function ($q) {
                    $q->where('property_id', $this->propertyFilter);
                });
            })
            ->orderByRaw("FIELD(priority, 'emergency', 'high', 'medium', 'low')")
            ->orderByRaw("FIELD(status, 'new', 'assigned', 'in_progress', 'on_hold', 'completed', 'cancelled')")
            ->latest()
            ->paginate(12);

        $properties = Property::where('company_id', $companyId)->orderBy('name')->get();

        $stats = [
            'total' => MaintenanceRequest::where('company_id', $companyId)->count(),
            'open' => MaintenanceRequest::where('company_id', $companyId)->whereIn('status', ['new', 'assigned', 'in_progress'])->count(),
            'new' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'new')->count(),
            'in_progress' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'in_progress')->count(),
            'completed' => MaintenanceRequest::where('company_id', $companyId)->where('status', 'completed')->count(),
            'emergency' => MaintenanceRequest::where('company_id', $companyId)->where('priority', 'emergency')->whereNotIn('status', ['completed', 'cancelled'])->count(),
        ];

        return view('livewire.maintenance.maintenance-list', [
            'requests' => $requests,
            'properties' => $properties,
            'stats' => $stats,
        ]);
    }
}