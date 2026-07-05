<?php

namespace Modules\Notification\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class NotificationDeviceToken extends TenantAwareModel
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'device_type',
        'token',
        'is_active',
        'last_seen_at',
        'meta',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_seen_at' => 'datetime',
            'meta' => 'array',
        ];
    }
}
