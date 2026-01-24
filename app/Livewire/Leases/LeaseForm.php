<?php

namespace App\Livewire\Leases;

use Livewire\Component;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\ActivityLog;

class LeaseForm extends Component
{
    public ?Lease $lease = null;
    public $property_id = '';
    public $unit_id = '';
    public $tenant_id = '';
    public $start_date = '';
    public $end_date = '';
    public $rent_amount = '';
    public $security_deposit = '';
    public $payment_due_day = '1';
    public $lease_type = 'fixed';
    public $status = 'pending';
    public $notes = '';
    public $units = [];

    public function mount($leaseId = null)
    {
        if ($leaseId) {
            $this->lease = Lease::where('company_id', auth()->user()->company_id)
                ->with('unit')
                ->findOrFail($leaseId);

            $this->property_id = $this->lease->unit->property_id;
            $this->unit_id = $this->lease->unit_id;
            $this->tenant_id = $this->lease->tenant_id;
            $this->start_date = $this->lease->start_date->format('Y-m-d');
            $this->end_date = $this->lease->end_date->format('Y-m-d');
            $this->rent_amount = $this->lease->rent_amount;
            $this->security_deposit = $this->lease->security_deposit ?? '';
            $this->payment_due_day = $this->lease->payment_due_day ?? '1';
            $this->lease_type = $this->lease->lease_type ?? 'fixed';
            $this->status = $this->lease->status;
            $this->notes = $this->lease->notes ?? '';
            $this->loadUnits();
        } else {
            $this->start_date = now()->format('Y-m-d');
            $this->end_date = now()->addYear()->format('Y-m-d');
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
                ->where(function ($query) {
                    $query->where('status', 'vacant')
                          ->orWhere('id', $this->unit_id);
                })
                ->orderBy('unit_number')
                ->get();
        } else {
            $this->units = [];
        }
    }

    public function setLeaseType($type)
    {
        $this->lease_type = $type;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function save()
    {
        $this->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'required|exists:units,id',
            'tenant_id' => 'required|exists:tenants,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'rent_amount' => 'required|numeric|min:0',
            'security_deposit' => 'nullable|numeric|min:0',
            'payment_due_day' => 'required|integer|min:1|max:28',
            'lease_type' => 'required|in:fixed,month_to_month',
            'status' => 'required|in:pending,active,expired,terminated',
        ]);

        $data = [
            'company_id' => auth()->user()->company_id,
            'unit_id' => $this->unit_id,
            'tenant_id' => $this->tenant_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'rent_amount' => $this->rent_amount,
            'security_deposit' => $this->security_deposit ?: null,
            'payment_due_day' => $this->payment_due_day,
            'lease_type' => $this->lease_type,
            'status' => $this->status,
            'notes' => $this->notes ?: null,
        ];

        if ($this->lease) {
            $this->lease->update($data);
            ActivityLog::log('updated', $this->lease);
            session()->flash('success', 'Lease updated successfully.');
        } else {
            $lease = Lease::create($data);
            ActivityLog::log('created', $lease);
            session()->flash('success', 'Lease created successfully.');
        }

        $unit = Unit::find($this->unit_id);
        if ($unit && $this->status === 'active') {
            $unit->update(['status' => 'occupied']);
        }

        return redirect()->route('leases.index');
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;
        return view('livewire.leases.lease-form', [
            'properties' => Property::where('company_id', $companyId)->orderBy('name')->get(),
            'tenants' => Tenant::where('company_id', $companyId)
                ->whereIn('status', ['pending', 'active'])
                ->orderBy('last_name')
                ->get(),
        ]);
    }
}
