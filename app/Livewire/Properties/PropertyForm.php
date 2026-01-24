<?php

namespace App\Livewire\Properties;

use Livewire\Component;
use App\Models\Property;
use App\Models\ActivityLog;

class PropertyForm extends Component
{
    public ?Property $property = null;
    
    public $name = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zip = '';
    public $type = 'residential';
    public $notes = '';

    public function mount($propertyId = null)
    {
        if ($propertyId) {
            $this->property = Property::where('company_id', auth()->user()->company_id)
                ->findOrFail($propertyId);
            
            $this->name = $this->property->name;
            $this->address = $this->property->address;
            $this->city = $this->property->city;
            $this->state = $this->property->state;
            $this->zip = $this->property->zip;
            $this->type = $this->property->type;
            $this->notes = $this->property->notes ?? '';
        }
    }

    public function save()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:50',
            'zip' => 'required|string|max:20',
            'type' => 'required|in:residential,commercial,mixed',
            'notes' => 'nullable|string',
        ]);

        $validated['company_id'] = auth()->user()->company_id;

        if ($this->property) {
            $this->property->update($validated);
            ActivityLog::log('updated', $this->property);
            session()->flash('success', 'Property updated successfully.');
        } else {
            $property = Property::create($validated);
            ActivityLog::log('created', $property);
            session()->flash('success', 'Property created successfully.');
        }

        return redirect()->route('properties.index');
    }

    public function render()
    {
        return view('livewire.properties.property-form');
    }
}