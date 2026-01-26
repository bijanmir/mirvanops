<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    protected $fillable = [
        'company_id',
        'uploaded_by',
        'documentable_type',
        'documentable_id',
        'name',
        'original_filename',
        'filename',
        'path',
        'mime_type',
        'size',
        'category',
        'notes',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function documentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function getSizeForHumansAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getIconAttribute(): string
    {
        return match(true) {
            str_contains($this->mime_type, 'pdf') => '📄',
            str_contains($this->mime_type, 'image') => '🖼️',
            str_contains($this->mime_type, 'word') => '📝',
            str_contains($this->mime_type, 'excel') || str_contains($this->mime_type, 'spreadsheet') => '📊',
            default => '📎',
        };
    }
}