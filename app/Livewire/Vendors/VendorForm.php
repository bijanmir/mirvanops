<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use App\Models\Vendor;
use App\Models\ActivityLog;

class VendorForm extends Component
{
    public ?Vendor $vendor = null;

    public $name = '';
    public $email = '';
    public $phone = '';
    public $specialty = '';
    public $hourly_rate = '';
    public $address = '';
    public $city = '';
    public $state = '';
    public $zip = '';
    public $notes = '';
    public $is_active = true;

    public $specialtyOptions = [
        'Plumbing',
        'Electrical',
        'HVAC',
        'Appliance Repair',
        'General Maintenance',
        'Landscaping',
        'Pest Control',
        'Roofing',
        'Painting',
        'Flooring',
        'Locksmith',
        'Cleaning',
        'Other',
    ];

    public function mount($vendorId = null)
    {
        if ($vendorId) {
            $this->vendor = Vendor::where('company_id', auth()->user()->company_id)
                ->findOrFail($vendorId);

            $this->name = $this->vendor->name;
            $this->email = $this->vendor->email ?? '';
            $this->phone = $this->vendor->phone ?? '';
            $this->specialty = $this->vendor->specialty ?? '';
            $this->hourly_rate = $this->vendor->hourly_rate ?? '';
            $this->address = $this->vendor->address ?? '';
            $this->city = $this->vendor->city ?? '';
            $this->state = $this->vendor->state ?? '';
            $this->zip = $this->vendor->zip ?? '';
            $this->notes = $this->vendor->notes ?? '';
            $this->is_active = $this->vendor->is_active;
        }
    }

    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;
    }

    public function save()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'specialty' => 'required|string|max:100',
            'hourly_rate' => 'nullable|numeric|min:0',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:50',
            'zip' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
            'is_active' => 'boolean',
        ];

        $this->validate($rules);

        $data = [
            'company_id' => auth()->user()->company_id,
            'name' => $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'specialty' => $this->specialty,
            'hourly_rate' => $this->hourly_rate ?: null,
            'address' => $this->address ?: null,
            'city' => $this->city ?: null,
            'state' => $this->state ?: null,
            'zip' => $this->zip ?: null,
            'notes' => $this->notes ?: null,
            'is_active' => $this->is_active,
        ];

        if ($this->vendor) {
            $this->vendor->update($data);
            ActivityLog::log('updated', $this->vendor);
            session()->flash('success', 'Vendor updated successfully.');
        } else {
            $vendor = Vendor::create($data);
            ActivityLog::log('created', $vendor);
            session()->flash('success', 'Vendor created successfully.');
        }

        return redirect()->route('vendors.index');
    }

    public function render()
    {
        return view('livewire.vendors.vendor-form');
    }
}