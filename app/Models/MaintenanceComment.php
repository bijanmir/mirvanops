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
        'is_system',
        'edited_at',
    ];

    protected $casts = [
        'is_internal' => 'boolean',
        'is_system' => 'boolean',
        'edited_at' => 'datetime',
    ];

    public function maintenanceRequest(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isEdited(): bool
    {
        return $this->edited_at !== null;
    }

    public function canEdit(): bool
    {
        return $this->user_id === auth()->id() && !$this->is_system;
    }

    public function canDelete(): bool
    {
        return $this->user_id === auth()->id() && !$this->is_system;
    }
}