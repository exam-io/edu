<?php

namespace Modules\Communication\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class Announcement extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'announcements';

    protected $fillable = [
        'tenant_id',
        'title',
        'body',
        'target_user_ids',
        'channels',
        'status',
        'publish_at',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'target_user_ids' => 'array',
            'channels' => 'array',
            'publish_at' => 'datetime',
            'published_at' => 'datetime',
        ];
    }
}
