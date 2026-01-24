<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'notes',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function leases(): BelongsToMany
    {
        return $this->belongsToMany(Lease::class)->withPivot('is_primary')->withTimestamps();
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}