<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'action',
        'model_type',
        'model_id',
        'changes',
        'ip_address',
    ];

    protected $casts = [
        'changes' => 'array',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        Model $model,
        ?array $changes = null,
        ?int $userId = null,
        ?int $companyId = null
    ): self {
        return self::create([
            'company_id' => $companyId ?? auth()->user()?->company_id,
            'user_id' => $userId ?? auth()->id(),
            'action' => $action,
            'model_type' => class_basename($model),
            'model_id' => $model->id,
            'changes' => $changes,
            'ip_address' => request()->ip(),
        ]);
    }
}