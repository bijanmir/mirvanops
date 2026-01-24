<?php

namespace App\Livewire\Tenants;

use Livewire\Component;
use App\Models\Tenant;

class TenantShow extends Component
{
    public Tenant $tenant;

    public function mount($tenantId)
    {
        $this->tenant = Tenant::where('company_id', auth()->user()->company_id)
            ->with(['leases.unit.property'])
            ->findOrFail($tenantId);
    }

    public function render()
    {
        return view('livewire.tenants.tenant-show');
    }
}