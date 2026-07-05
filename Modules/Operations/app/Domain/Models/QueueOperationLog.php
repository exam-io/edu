<?php

namespace Modules\Operations\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class QueueOperationLog extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'operation',
        'status',
        'meta',
        'executed_at',
    ];

    protected $casts = [
        'meta' => 'array',
        'executed_at' => 'datetime',
    ];
}
