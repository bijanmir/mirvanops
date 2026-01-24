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
        'assigned_by',
        'title',
        'description',
        'category',
        'priority',
        'status',
        'submitted_by_name',
        'submitted_by_email',
        'submitted_by_phone',
        'permission_to_enter',
        'assigned_at',
        'completed_at',
    ];

    protected $casts = [
        'permission_to_enter' => 'boolean',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
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

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(MaintenanceComment::class)->orderBy('created_at', 'asc');
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
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'new' => 'blue',
            'assigned' => 'purple',
            'in_progress' => 'yellow',
            'completed' => 'green',
            'closed' => 'gray',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}