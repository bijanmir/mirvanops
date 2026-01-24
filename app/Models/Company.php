<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Cashier\Billable;

class Company extends Model
{
    use Billable;

    protected $fillable = [
        'name',
        'slug',
        'phone',
        'email',
        'address',
        'city',
        'state',
        'zip',
        'timezone',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}