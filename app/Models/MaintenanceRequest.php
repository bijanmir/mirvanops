<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceRequest extends Model
{
    protected $fillable = [
        'company_id',
        'unit_id',
        'lease_id',
        'vendor_id',
        'assigned_to',
        'reported_by',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'scheduled_date',
        'completed_date',
        'estimated_cost',
        'actual_cost',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'completed_date' => 'date',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MaintenanceComment::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(MaintenancePhoto::class);
    }

    public function isOpen(): bool
    {
        return in_array($this->status, ['new', 'assigned', 'in_progress']);
    }

    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'emergency' => 'red',
            'high' => 'orange',
            'medium' => 'amber',
            'low' => 'green',
            default => 'gray',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'assigned' => 'purple',
            'in_progress' => 'amber',
            'on_hold' => 'gray',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}