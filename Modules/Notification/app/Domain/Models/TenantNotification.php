<?php

namespace Modules\Notification\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class TenantNotification extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'notification_type',
        'title',
        'body',
        'status',
        'channels',
        'data',
        'sent_at',
        'read_at',
    ];

    protected function casts(): array
    {
        return [
            'channels' => 'array',
            'data' => 'array',
            'sent_at' => 'datetime',
            'read_at' => 'datetime',
        ];
    }
}
