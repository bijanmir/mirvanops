<?php

namespace App\Livewire\Properties;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Property;

class PropertyList extends Component
{
    use WithPagination;

    public $search = '';
    public $typeFilter = '';
    public $showDeleteModal = false;
    public $propertyToDelete = null;
    public $propertyToDeleteName = '';
    public $propertyToDeleteUnitCount = 0;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function confirmDelete($propertyId)
    {
        $property = Property::where('company_id', auth()->user()->company_id)
            ->withCount('units')
            ->findOrFail($propertyId);

        $this->propertyToDelete = $propertyId;
        $this->propertyToDeleteName = $property->name;
        $this->propertyToDeleteUnitCount = $property->units_count;
        $this->showDeleteModal = true;
    }

    public function deleteProperty()
    {
        $property = Property::where('company_id', auth()->user()->company_id)
            ->withCount('units')
            ->findOrFail($this->propertyToDelete);

        // Block deletion if property has units
        if ($property->units_count > 0) {
            session()->flash('error', 'Cannot delete property with units. Please delete all units first.');
            $this->showDeleteModal = false;
            $this->propertyToDelete = null;
            return;
        }

        $property->delete();

        $this->showDeleteModal = false;
        $this->propertyToDelete = null;
        $this->propertyToDeleteName = '';
        $this->propertyToDeleteUnitCount = 0;

        session()->flash('success', 'Property deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->propertyToDelete = null;
        $this->propertyToDeleteName = '';
        $this->propertyToDeleteUnitCount = 0;
    }

    public function render()
    {
        $properties = Property::where('company_id', auth()->user()->company_id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('address', 'like', '%' . $this->search . '%')
                      ->orWhere('city', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('type', $this->typeFilter);
            })
            ->withCount('units')
            ->latest()
            ->paginate(12);

        return view('livewire.properties.property-list', [
            'properties' => $properties,
        ]);
    }
}
