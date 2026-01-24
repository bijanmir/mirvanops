<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Property extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'address',
        'city',
        'state',
        'zip',
        'type',
        'unit_count',
        'notes',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function maintenanceRequests(): HasManyThrough
    {
        return $this->hasManyThrough(MaintenanceRequest::class, Unit::class);
    }

    public function getFullAddressAttribute(): string
    {
        return "{$this->address}, {$this->city}, {$this->state} {$this->zip}";
    }

    public function updateUnitCount(): void
    {
        $this->update(['unit_count' => $this->units()->count()]);
    }
}