<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class TenantPortalToken extends Model
{
    protected $fillable = [
        'company_id',
        'unit_id',
        'lease_id',
        'token',
        'expires_at',
        'last_used_at',
        'revoked_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
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

    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public function isValid(): bool
    {
        if ($this->revoked_at) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        return true;
    }

    public function markUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }
}