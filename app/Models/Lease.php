<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasDocuments;

class Lease extends Model
{
    use HasDocuments;
    protected $fillable = [
        'company_id',
        'unit_id',
        'tenant_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit_amount',
        'security_deposit',
        'payment_due_day',
        'lease_type',
        'status',
        'move_in_date',
        'move_out_date',
        'has_pet',
        'pet_type',
        'pet_deposit',
        'pet_rent',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'move_in_date' => 'date',
        'move_out_date' => 'date',
        'rent_amount' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'security_deposit' => 'decimal:2',
        'pet_deposit' => 'decimal:2',
        'pet_rent' => 'decimal:2',
        'payment_due_day' => 'integer',
        'has_pet' => 'boolean',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalMonthlyRentAttribute(): float
    {
        return $this->rent_amount + ($this->pet_rent ?? 0);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isExpiringSoon(): bool
    {
        return $this->status === 'active' 
            && $this->end_date->diffInDays(now()) <= 30 
            && $this->end_date->isFuture();
    }
}
