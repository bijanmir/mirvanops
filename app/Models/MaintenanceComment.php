<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceComment extends Model
{
    protected $fillable = [
        'maintenance_request_id',
        'user_id',
        'body',
        'is_internal',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
    ];

    public function maintenanceRequest(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}