<?php

namespace Modules\Campaign\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class Campaign extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'campaigns';

    protected $fillable = [
        'tenant_id',
        'name',
        'campaign_type',
        'subject',
        'message',
        'channels',
        'status',
        'scheduled_at',
        'launched_at',
    ];

    protected function casts(): array
    {
        return [
            'channels' => 'array',
            'scheduled_at' => 'datetime',
            'launched_at' => 'datetime',
        ];
    }
}
