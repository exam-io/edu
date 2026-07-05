<?php

namespace Modules\Audit\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AuditLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'actor_user_id',
        'actor_type',
        'action',
        'resource_type',
        'resource_id',
        'before_state',
        'after_state',
        'context',
        'occurred_at',
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
        'context' => 'array',
        'occurred_at' => 'datetime',
    ];
}
