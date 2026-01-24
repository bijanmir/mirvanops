<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lease extends Model
{
    protected $fillable = [
        'company_id',
        'unit_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit_amount',
        'status',
        'move_in_date',
        'move_out_date',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'rent_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class)->withPivot('is_primary')->withTimestamps();
    }

    public function primaryTenant()
    {
        return $this->tenants()->wherePivot('is_primary', true)->first();
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->end_date->isBetween(now(), now()->addDays($days));
    }
}