<?php

namespace Modules\ContentProcessing\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentSource extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'uploaded_by',
        'title',
        'source_type',
        'source_ref',
        'mime_type',
        'status',
        'meta',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'processed_at' => 'datetime',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    public function extractions(): HasMany
    {
        return $this->hasMany(ContentExtraction::class, 'content_source_id');
    }
}
