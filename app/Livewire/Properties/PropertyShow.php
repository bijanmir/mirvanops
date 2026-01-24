<?php

namespace App\Livewire\Properties;

use Livewire\Component;
use App\Models\Property;
use App\Models\Unit;
use App\Models\ActivityLog;

class PropertyShow extends Component
{
    public Property $property;
    
    public $showUnitModal = false;
    public $showDeleteUnitModal = false;
    public $editingUnit = null;
    public $unitToDelete = null;
    public $statusFilter = '';
    
    // Unit form fields
    public $unit_number = '';
    public $beds = '';
    public $baths = '';
    public $sqft = '';
    public $market_rent = '';
    public $status = 'vacant';
    public $unit_notes = '';

    public function mount($propertyId)
    {
        $this->property = Property::where('company_id', auth()->user()->company_id)
            ->with(['units' => function($query) {
                $query->orderBy('unit_number');
            }])
            ->findOrFail($propertyId);
    }

    public function setStatusFilter($status)
    {
        $this->statusFilter = $status;
    }

    public function setUnitStatus($status)
    {
        $this->status = $status;
    }

    public function openUnitModal($unitId = null)
    {
        if ($unitId) {
            $unit = Unit::where('company_id', auth()->user()->company_id)->findOrFail($unitId);
            $this->editingUnit = $unit;
            $this->unit_number = $unit->unit_number;
            $this->beds = $unit->beds;
            $this->baths = $unit->baths;
            $this->sqft = $unit->sqft;
            $this->market_rent = $unit->market_rent;
            $this->status = $unit->status;
            $this->unit_notes = $unit->notes ?? '';
        } else {
            $this->resetUnitForm();
        }
        $this->showUnitModal = true;
    }

    public function closeUnitModal()
    {
        $this->showUnitModal = false;
        $this->resetUnitForm();
    }

    public function resetUnitForm()
    {
        $this->editingUnit = null;
        $this->unit_number = '';
        $this->beds = '';
        $this->baths = '';
        $this->sqft = '';
        $this->market_rent = '';
        $this->status = 'vacant';
        $this->unit_notes = '';
        $this->resetValidation();
    }

    public function saveUnit()
    {
        $validated = $this->validate([
            'unit_number' => 'required|string|max:50',
            'beds' => 'nullable|numeric|min:0|max:20',
            'baths' => 'nullable|numeric|min:0|max:20',
            'sqft' => 'nullable|integer|min:0|max:100000',
            'market_rent' => 'nullable|numeric|min:0|max:1000000',
            'status' => 'required|in:vacant,occupied,maintenance,offline',
            'unit_notes' => 'nullable|string',
        ]);

        $data = [
            'company_id' => auth()->user()->company_id,
            'property_id' => $this->property->id,
            'unit_number' => $this->unit_number,
            'beds' => $this->beds ?: null,
            'baths' => $this->baths ?: null,
            'sqft' => $this->sqft ?: null,
            'market_rent' => $this->market_rent ?: null,
            'status' => $this->status,
            'notes' => $this->unit_notes ?: null,
        ];

        if ($this->editingUnit) {
            $this->editingUnit->update($data);
            ActivityLog::log('updated', $this->editingUnit);
            session()->flash('success', 'Unit updated successfully.');
        } else {
            $unit = Unit::create($data);
            ActivityLog::log('created', $unit);
            session()->flash('success', 'Unit created successfully.');
        }

        $this->closeUnitModal();
        
        // Refresh the property with its units
        $this->property = Property::where('company_id', auth()->user()->company_id)
            ->with(['units' => function($query) {
                $query->orderBy('unit_number');
            }])
            ->find($this->property->id);
        
        // Update unit count
        $this->property->update(['unit_count' => $this->property->units->count()]);
    }

    public function confirmDeleteUnit($unitId)
    {
        $this->unitToDelete = $unitId;
        $this->showDeleteUnitModal = true;
    }

    public function deleteUnit()
    {
        $unit = Unit::where('company_id', auth()->user()->company_id)->findOrFail($this->unitToDelete);
        ActivityLog::log('deleted', $unit);
        $unit->delete();
        
        $this->showDeleteUnitModal = false;
        $this->unitToDelete = null;
        
        // Refresh the property with its units
        $this->property = Property::where('company_id', auth()->user()->company_id)
            ->with(['units' => function($query) {
                $query->orderBy('unit_number');
            }])
            ->find($this->property->id);
        
        // Update unit count
        $this->property->update(['unit_count' => $this->property->units->count()]);
        
        session()->flash('success', 'Unit deleted successfully.');
    }

    public function cancelDeleteUnit()
    {
        $this->showDeleteUnitModal = false;
        $this->unitToDelete = null;
    }

    public function render()
    {
        return view('livewire.properties.property-show');
    }
}