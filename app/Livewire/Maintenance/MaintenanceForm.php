<?php

namespace App\Livewire\Maintenance;

use Livewire\Component;
use App\Models\MaintenanceRequest;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Vendor;
use App\Models\ActivityLog;

class MaintenanceForm extends Component
{
    public ?MaintenanceRequest $request = null;

    public $property_id = '';
    public $unit_id = '';
    public $title = '';
    public $description = '';
    public $category = 'general';
    public $priority = 'medium';
    public $status = 'new';
    public $vendor_id = '';
    public $scheduled_date = '';
    public $estimated_cost = '';
    public $notes = '';

    public $units = [];

    public function mount($requestId = null)
    {
        if ($requestId) {
            $this->request = MaintenanceRequest::where('company_id', auth()->user()->company_id)
                ->with('unit')
                ->findOrFail($requestId);

            $this->property_id = $this->request->unit->property_id;
            $this->unit_id = $this->request->unit_id;
            $this->title = $this->request->title;
            $this->description = $this->request->description;
            $this->category = $this->request->category;
            $this->priority = $this->request->priority;
            $this->status = $this->request->status;
            $this->vendor_id = $this->request->vendor_id ?? '';
            $this->scheduled_date = $this->request->scheduled_date?->format('Y-m-d') ?? '';
            $this->estimated_cost = $this->request->estimated_cost ?? '';
            $this->notes = $this->request->notes ?? '';

            $this->loadUnits();
        }
    }

    public function updatedPropertyId()
    {
        $this->unit_id = '';
        $this->loadUnits();
    }

    public function loadUnits()
    {
        if ($this->property_id) {
            $this->units = Unit::where('company_id', auth()->user()->company_id)
                ->where('property_id', $this->property_id)
                ->orderBy('unit_number')
                ->get();
        } else {
            $this->units = [];
        }
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function save()
    {
        $rules = [
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:units,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:plumbing,electrical,hvac,appliance,structural,pest,general,other',
            'priority' => 'required|in:low,medium,high,emergency',
            'status' => 'required|in:new,assigned,in_progress,on_hold,completed,cancelled',
            'vendor_id' => 'nullable|exists:vendors,id',
            'scheduled_date' => 'nullable|date',
            'estimated_cost' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ];

        $validated = $this->validate($rules);

        $data = [
            'company_id' => auth()->user()->company_id,
            'unit_id' => $this->unit_id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'priority' => $this->priority,
            'status' => $this->status,
            'vendor_id' => $this->vendor_id ?: null,
            'scheduled_date' => $this->scheduled_date ?: null,
            'estimated_cost' => $this->estimated_cost ?: null,
            'notes' => $this->notes ?: null,
        ];

        if ($this->request) {
            $this->request->update($data);
            ActivityLog::log('updated', $this->request);
            session()->flash('success', 'Maintenance request updated successfully.');
        } else {
            $data['reported_by'] = auth()->id();
            $request = MaintenanceRequest::create($data);
            ActivityLog::log('created', $request);
            session()->flash('success', 'Maintenance request created successfully.');
        }

        return redirect()->route('maintenance.index');
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;

        return view('livewire.maintenance.maintenance-form', [
            'properties' => Property::where('company_id', $companyId)->orderBy('name')->get(),
            'vendors' => Vendor::where('company_id', $companyId)->where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}