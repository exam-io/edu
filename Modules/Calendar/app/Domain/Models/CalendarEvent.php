<?php

namespace Modules\Calendar\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class CalendarEvent extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'start_at',
        'end_at',
        'all_day',
        'event_type',
        'status',
        'source_type',
        'source_id',
        'url',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'all_day' => 'boolean',
            'meta' => 'array',
        ];
    }
}
