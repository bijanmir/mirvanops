<?php

namespace App\Livewire\Tenants;

use Livewire\Component;
use App\Models\Tenant;
use App\Models\ActivityLog;

class TenantForm extends Component
{
    public ?Tenant $tenant = null;

    public $first_name = '';
    public $last_name = '';
    public $email = '';
    public $phone = '';
    public $alternate_phone = '';
    public $date_of_birth = '';
    public $ssn_last_four = '';
    public $drivers_license = '';
    public $emergency_contact_name = '';
    public $emergency_contact_phone = '';
    public $employer = '';
    public $employer_phone = '';
    public $annual_income = '';
    public $status = 'pending';
    public $notes = '';

    public function mount($tenantId = null)
    {
        if ($tenantId) {
            $this->tenant = Tenant::where('company_id', auth()->user()->company_id)
                ->findOrFail($tenantId);

            $this->first_name = $this->tenant->first_name;
            $this->last_name = $this->tenant->last_name;
            $this->email = $this->tenant->email ?? '';
            $this->phone = $this->tenant->phone ?? '';
            $this->alternate_phone = $this->tenant->alternate_phone ?? '';
            $this->date_of_birth = $this->tenant->date_of_birth?->format('Y-m-d') ?? '';
            $this->ssn_last_four = $this->tenant->ssn_last_four ?? '';
            $this->drivers_license = $this->tenant->drivers_license ?? '';
            $this->emergency_contact_name = $this->tenant->emergency_contact_name ?? '';
            $this->emergency_contact_phone = $this->tenant->emergency_contact_phone ?? '';
            $this->employer = $this->tenant->employer ?? '';
            $this->employer_phone = $this->tenant->employer_phone ?? '';
            $this->annual_income = $this->tenant->annual_income ?? '';
            $this->status = $this->tenant->status;
            $this->notes = $this->tenant->notes ?? '';
        }
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function save()
    {
        $rules = [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'alternate_phone' => 'nullable|string|max:50',
            'date_of_birth' => 'nullable|date',
            'ssn_last_four' => 'nullable|string|max:4',
            'drivers_license' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:50',
            'employer' => 'nullable|string|max:255',
            'employer_phone' => 'nullable|string|max:50',
            'annual_income' => 'nullable|numeric|min:0',
            'status' => 'required|in:pending,active,past,evicted',
            'notes' => 'nullable|string',
        ];

        $this->validate($rules);

        $data = [
            'company_id' => auth()->user()->company_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'alternate_phone' => $this->alternate_phone ?: null,
            'date_of_birth' => $this->date_of_birth ?: null,
            'ssn_last_four' => $this->ssn_last_four ?: null,
            'drivers_license' => $this->drivers_license ?: null,
            'emergency_contact_name' => $this->emergency_contact_name ?: null,
            'emergency_contact_phone' => $this->emergency_contact_phone ?: null,
            'employer' => $this->employer ?: null,
            'employer_phone' => $this->employer_phone ?: null,
            'annual_income' => $this->annual_income ?: null,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
        ];

        if ($this->tenant) {
            $this->tenant->update($data);
            ActivityLog::log('updated', $this->tenant);
            session()->flash('success', 'Tenant updated successfully.');
        } else {
            $tenant = Tenant::create($data);
            ActivityLog::log('created', $tenant);
            session()->flash('success', 'Tenant created successfully.');
        }

        return redirect()->route('tenants.index');
    }

    public function render()
    {
        return view('livewire.tenants.tenant-form');
    }
}