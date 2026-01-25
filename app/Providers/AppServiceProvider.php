<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\CacheObserver;
use App\Models\Payment;
use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use App\Models\MaintenanceRequest;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Clear dashboard cache when these models change
        $models = [
            Payment::class,
            Lease::class,
            Tenant::class,
            Property::class,
            Unit::class,
            MaintenanceRequest::class,
        ];

        foreach ($models as $model) {
            $model::observe(CacheObserver::class);
        }
    }
}