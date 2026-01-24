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
        $this->propertyToDelete = $propertyId;
        $this->showDeleteModal = true;
    }

    public function deleteProperty()
    {
        $property = Property::where('company_id', auth()->user()->company_id)
            ->findOrFail($this->propertyToDelete);
        
        $property->delete();
        
        $this->showDeleteModal = false;
        $this->propertyToDelete = null;
        
        session()->flash('success', 'Property deleted successfully.');
    }

    public function cancelDelete()
    {
        $this->showDeleteModal = false;
        $this->propertyToDelete = null;
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