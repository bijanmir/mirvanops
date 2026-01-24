<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'company_id',
        'lease_id',
        'tenant_id',
        'amount',
        'payment_date',
        'payment_method',
        'period_covered',
        'reference_number',
        'late_fee',
        'status',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'late_fee' => 'decimal:2',
    ];

    public const PAYMENT_METHODS = [
        'ach' => 'ACH / Bank Transfer',
        'check' => 'Check',
        'cash' => 'Cash',
        'money_order' => 'Money Order',
        'zelle' => 'Zelle',
        'venmo' => 'Venmo',
        'card' => 'Credit/Debit Card',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'completed' => 'Completed',
        'pending' => 'Pending',
        'failed' => 'Failed',
        'refunded' => 'Refunded',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function lease(): BelongsTo
    {
        return $this->belongsTo(Lease::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getTotalAmountAttribute(): float
    {
        return $this->amount + ($this->late_fee ?? 0);
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return self::PAYMENT_METHODS[$this->payment_method] ?? $this->payment_method;
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}