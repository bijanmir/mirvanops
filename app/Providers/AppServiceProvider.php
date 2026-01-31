<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register policies
        Gate::policy(\App\Models\Property::class, \App\Policies\PropertyPolicy::class);
        Gate::policy(\App\Models\Unit::class, \App\Policies\UnitPolicy::class);
        Gate::policy(\App\Models\Tenant::class, \App\Policies\TenantPolicy::class);
        Gate::policy(\App\Models\Lease::class, \App\Policies\LeasePolicy::class);
        Gate::policy(\App\Models\Payment::class, \App\Policies\PaymentPolicy::class);
        Gate::policy(\App\Models\MaintenanceRequest::class, \App\Policies\MaintenanceRequestPolicy::class);
        Gate::policy(\App\Models\Vendor::class, \App\Policies\VendorPolicy::class);
    }
}
