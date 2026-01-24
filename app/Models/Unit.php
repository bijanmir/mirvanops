<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Unit extends Model
{
    protected $fillable = [
        'company_id',
        'property_id',
        'unit_number',
        'beds',
        'baths',
        'sqft',
        'market_rent',
        'status',
        'notes',
    ];

    protected $casts = [
        'beds' => 'decimal:1',
        'baths' => 'decimal:1',
        'market_rent' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function currentLease(): HasOne
    {
        return $this->hasOne(Lease::class)->where('status', 'active');
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function portalTokens(): HasMany
    {
        return $this->hasMany(TenantPortalToken::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->property->name . ' - Unit ' . $this->unit_number;
    }
}