<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasDocuments;
class Tenant extends Model
{
    use HasDocuments;
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'date_of_birth',
        'ssn_last_four',
        'drivers_license',
        'emergency_contact_name',
        'emergency_contact_phone',
        'employer',
        'employer_phone',
        'annual_income',
        'status',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'annual_income' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leases(): HasMany
    {
        return $this->hasMany(Lease::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}