<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'zip',
        'timezone',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'subscription_id',
        'plan',
        'subscription_status',
        'trial_ends_at',
        'subscription_ends_at',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'subscription_ends_at' => 'datetime',
    ];

    // Relationships
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

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(Vendor::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    // Subscription helpers
    public function getPlanConfig(): array
    {
        return config('stripe.plans.' . ($this->plan ?? 'free'), config('stripe.plans.free'));
    }

    public function getUnitLimit(): int
    {
        return $this->getPlanConfig()['unit_limit'];
    }

    public function canAddUnits(int $count = 1): bool
    {
        return ($this->units()->count() + $count) <= $this->getUnitLimit();
    }

    public function isOverLimit(): bool
    {
        return $this->units()->count() > $this->getUnitLimit();
    }

    public function hasActiveSubscription(): bool
    {
        return in_array($this->subscription_status, ['active', 'trialing']);
    }

    public function onFreePlan(): bool
    {
        return ($this->plan ?? 'free') === 'free';
    }

    public function onPaidPlan(): bool
    {
        return !$this->onFreePlan();
    }
}
