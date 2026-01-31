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
    public $showDeleteModal = false;
    public $editingUnit = null;
    public $unitToDelete = null;
    
    public $unit_number = '';
    public $floor = '';
    public $bedrooms = '1';
    public $bathrooms = '1';
    public $square_feet = '';
    public $market_rent = '';
    public $status = 'vacant';
    public $description = '';

    public $statusFilter = '';

    public function mount($propertyId)
    {
        $this->property = Property::where('company_id', auth()->user()->company_id)
            ->findOrFail($propertyId);
    }

    public function openUnitModal($unitId = null)
    {
        // Check unit limit when adding new unit
        if (!$unitId) {
            $company = auth()->user()->company;
            if (!$company->canAddUnits()) {
                session()->flash('error', 'You have reached your unit limit (' . $company->getUnitLimit() . ' units). Please upgrade your plan to add more units.');
                return redirect()->route('billing.index');
            }
        }

        if ($unitId) {
            $unit = Unit::with('currentLease')->findOrFail($unitId);
            $this->editingUnit = $unit;
            $this->unit_number = $unit->unit_number;
            $this->floor = $unit->floor ?? '';
            $this->bedrooms = $unit->bedrooms;
            $this->bathrooms = $unit->bathrooms;
            $this->square_feet = $unit->square_feet ?? '';
            $this->market_rent = $unit->market_rent ?? '';
            $this->status = $unit->status;
            $this->description = $unit->description ?? '';
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
        $this->floor = '';
        $this->bedrooms = '1';
        $this->bathrooms = '1';
        $this->square_feet = '';
        $this->market_rent = '';
        $this->status = 'vacant';
        $this->description = '';
    }

    public function saveUnit()
    {
        // Check unit limit again before saving (in case of race condition)
        if (!$this->editingUnit) {
            $company = auth()->user()->company;
            if (!$company->canAddUnits()) {
                session()->flash('error', 'You have reached your unit limit. Please upgrade your plan to add more units.');
                $this->closeUnitModal();
                return;
            }
        }

        $this->validate([
            'unit_number' => 'required|string|max:50',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|numeric|min:0',
            'square_feet' => 'nullable|integer|min:0',
            'market_rent' => 'nullable|numeric|min:0',
            'status' => 'required|in:vacant,occupied,maintenance',
        ]);

        $data = [
            'company_id' => auth()->user()->company_id,
            'property_id' => $this->property->id,
            'unit_number' => $this->unit_number,
            'floor' => $this->floor ?: null,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'square_feet' => $this->square_feet ?: null,
            'market_rent' => $this->market_rent ?: null,
            'description' => $this->description ?: null,
        ];

        if ($this->editingUnit) {
            // Don't change status if there's an active lease
            if (!$this->editingUnit->currentLease) {
                $data['status'] = $this->status;
            }
            
            $this->editingUnit->update($data);
            ActivityLog::log('updated', $this->editingUnit);
            session()->flash('success', 'Unit updated successfully.');
        } else {
            $data['status'] = $this->status;
            $unit = Unit::create($data);
            ActivityLog::log('created', $unit);
            session()->flash('success', 'Unit added successfully.');
        }

        $this->closeUnitModal();
        $this->property->refresh();
    }

    public function confirmDeleteUnit($unitId)
    {
        $unit = Unit::with('currentLease')->findOrFail($unitId);
        
        if ($unit->currentLease) {
            session()->flash('error', 'Cannot delete unit with an active lease. Terminate the lease first.');
            return;
        }
        
        $this->unitToDelete = $unitId;
        $this->showDeleteModal = true;
    }

    public function deleteUnit()
    {
        $unit = Unit::with('currentLease')->findOrFail($this->unitToDelete);
        
        if ($unit->currentLease) {
            session()->flash('error', 'Cannot delete unit with an active lease.');
            $this->showDeleteModal = false;
            return;
        }
        
        ActivityLog::log('deleted', $unit);
        $unit->delete();
        
        $this->showDeleteModal = false;
        $this->unitToDelete = null;
        $this->property->refresh();
        
        session()->flash('success', 'Unit deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->unitToDelete = null;
    }

    public function render()
    {
        $company = auth()->user()->company;
        $units = $this->property->units()
            ->with(['currentLease.tenant'])
            ->when($this->statusFilter, function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->orderBy('unit_number')
            ->get();

        $stats = [
            'total' => $this->property->units()->count(),
            'occupied' => $this->property->units()->where('status', 'occupied')->count(),
            'vacant' => $this->property->units()->where('status', 'vacant')->count(),
            'maintenance' => $this->property->units()->where('status', 'maintenance')->count(),
        ];

        return view('livewire.properties.property-show', [
            'units' => $units,
            'stats' => $stats,
            'unitLimit' => $company->getUnitLimit(),
            'totalUnits' => $company->units()->count(),
            'canAddUnits' => $company->canAddUnits(),
        ]);
    }
}
