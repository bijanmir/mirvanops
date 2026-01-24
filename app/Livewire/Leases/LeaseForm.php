<?php

namespace App\Livewire\Leases;

use Livewire\Component;
use App\Models\Lease;
use App\Models\Property;
use App\Models\Unit;
use App\Models\Tenant;
use App\Models\ActivityLog;
use Illuminate\Support\Collection;

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
    
    // Pet fields
    public $has_pet = false;
    public $pet_type = '';
    public $pet_deposit = '';
    public $pet_rent = '';
    
    // Option to sync rent back to unit
    public $update_unit_rent = false;
    
    public $units = [];
    public $selectedUnitRent = null;
    
    // Tenant's existing leases warning - initialize as empty array
    public $tenantExistingLeases = [];

    public function mount($leaseId = null)
    {
        // Initialize collections
        $this->units = collect([]);
        $this->tenantExistingLeases = collect([]);
        
        if ($leaseId) {
            // Editing existing lease
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
            $this->status = $this->lease->status ?? 'pending';
            $this->notes = $this->lease->notes ?? '';
            
            // Pet fields
            $this->has_pet = $this->lease->has_pet ?? false;
            $this->pet_type = $this->lease->pet_type ?? '';
            $this->pet_deposit = $this->lease->pet_deposit ?? '';
            $this->pet_rent = $this->lease->pet_rent ?? '';
            
            $this->selectedUnitRent = $this->lease->unit->market_rent;
            $this->loadUnits();
            $this->checkTenantExistingLeases();
        } else {
            // Creating new lease - set defaults
            $this->start_date = now()->format('Y-m-d');
            $this->end_date = now()->addYear()->format('Y-m-d');
            
            // Check for query parameters (from unit card "Create Lease" link)
            $propertyId = request()->query('property_id');
            $unitId = request()->query('unit_id');
            $tenantId = request()->query('tenant_id');
            
            if ($propertyId) {
                $property = Property::where('company_id', auth()->user()->company_id)
                    ->find($propertyId);
                
                if ($property) {
                    $this->property_id = $property->id;
                    
                    if ($unitId) {
                        $unit = Unit::where('company_id', auth()->user()->company_id)
                            ->where('property_id', $property->id)
                            ->find($unitId);
                        
                        if ($unit) {
                            $this->unit_id = $unit->id;
                            $this->selectedUnitRent = $unit->market_rent;
                            
                            if ($unit->market_rent) {
                                $this->rent_amount = $unit->market_rent;
                                $this->security_deposit = $unit->market_rent;
                            }
                        }
                    }
                    
                    $this->loadUnits();
                }
            }
            
            if ($tenantId) {
                $tenant = Tenant::where('company_id', auth()->user()->company_id)
                    ->find($tenantId);
                if ($tenant) {
                    $this->tenant_id = $tenant->id;
                    $this->checkTenantExistingLeases();
                }
            }
        }
    }

    public function updatedPropertyId()
    {
        $this->unit_id = '';
        $this->rent_amount = '';
        $this->security_deposit = '';
        $this->selectedUnitRent = null;
        $this->loadUnits();
    }

    public function updatedTenantId()
    {
        $this->checkTenantExistingLeases();
    }

    public function checkTenantExistingLeases()
    {
        if ($this->tenant_id) {
            $query = Lease::where('company_id', auth()->user()->company_id)
                ->where('tenant_id', $this->tenant_id)
                ->whereIn('status', ['active', 'pending'])
                ->with('unit.property');
            
            // Exclude current lease if editing
            if ($this->lease) {
                $query->where('id', '!=', $this->lease->id);
            }
            
            $this->tenantExistingLeases = $query->get();
        } else {
            $this->tenantExistingLeases = collect([]);
        }
    }

    public function loadUnits()
    {
        if ($this->property_id) {
            $currentUnitId = $this->unit_id;
            $this->units = Unit::where('company_id', auth()->user()->company_id)
                ->where('property_id', $this->property_id)
                ->where(function ($query) use ($currentUnitId) {
                    $query->where('status', 'vacant');
                    if ($currentUnitId) {
                        $query->orWhere('id', $currentUnitId);
                    }
                })
                ->orderBy('unit_number')
                ->get();
        } else {
            $this->units = collect([]);
        }
    }

    public function updatedUnitId()
    {
        if ($this->unit_id) {
            $unit = Unit::find($this->unit_id);
            if ($unit) {
                $this->selectedUnitRent = $unit->market_rent;
                if (!$this->rent_amount && $unit->market_rent) {
                    $this->rent_amount = $unit->market_rent;
                }
                if (!$this->security_deposit && $unit->market_rent) {
                    $this->security_deposit = $unit->market_rent;
                }
            }
        } else {
            $this->selectedUnitRent = null;
        }
    }

    public function useUnitRent()
    {
        if ($this->selectedUnitRent) {
            $this->rent_amount = $this->selectedUnitRent;
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
            'pet_deposit' => 'nullable|numeric|min:0',
            'pet_rent' => 'nullable|numeric|min:0',
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
            'has_pet' => $this->has_pet,
            'pet_type' => $this->has_pet ? $this->pet_type : null,
            'pet_deposit' => $this->has_pet ? ($this->pet_deposit ?: null) : null,
            'pet_rent' => $this->has_pet ? ($this->pet_rent ?: null) : null,
            'notes' => $this->notes ?: null,
        ];

        $oldUnitId = null;

        if ($this->lease) {
            $oldUnitId = $this->lease->unit_id;
            $this->lease->update($data);
            ActivityLog::log('updated', $this->lease);
            session()->flash('success', 'Lease updated successfully.');
        } else {
            $lease = Lease::create($data);
            ActivityLog::log('created', $lease);
            session()->flash('success', 'Lease created successfully.');
        }

        if ($this->update_unit_rent) {
            Unit::find($this->unit_id)?->update(['market_rent' => $this->rent_amount]);
        }

        if ($oldUnitId && $oldUnitId != $this->unit_id) {
            Unit::find($oldUnitId)?->update(['status' => 'vacant']);
        }

        $unit = Unit::find($this->unit_id);
        if ($unit) {
            if (in_array($this->status, ['active', 'pending'])) {
                $unit->update(['status' => 'occupied']);
            } elseif (in_array($this->status, ['expired', 'terminated'])) {
                $unit->update(['status' => 'vacant']);
            }
        }

        if ($this->status === 'active') {
            Tenant::find($this->tenant_id)?->update(['status' => 'active']);
        }

        return redirect()->route('leases.index');
    }

    public function render()
    {
        $companyId = auth()->user()->company_id;
        
        // Get tenants with their active lease count
        $tenants = Tenant::where('company_id', $companyId)
            ->whereIn('status', ['pending', 'active'])
            ->withCount(['leases' => function ($query) {
                $query->whereIn('status', ['active', 'pending']);
            }])
            ->orderBy('last_name')
            ->get();
        
        return view('livewire.leases.lease-form', [
            'properties' => Property::where('company_id', $companyId)->orderBy('name')->get(),
            'tenants' => $tenants,
        ]);
    }
}
