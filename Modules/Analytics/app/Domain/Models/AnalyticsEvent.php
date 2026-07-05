<?php

namespace Modules\Analytics\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class AnalyticsEvent extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'event_name',
        'source_module',
        'entity_type',
        'entity_id',
        'payload',
        'occurred_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'occurred_at' => 'datetime',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
