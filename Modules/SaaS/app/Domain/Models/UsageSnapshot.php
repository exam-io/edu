<?php

namespace Modules\SaaS\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsageSnapshot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'snapshot_date',
        'metrics',
        'mrr',
        'arr',
        'active_subscribers',
        'meta',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'metrics' => 'array',
        'mrr' => 'float',
        'arr' => 'float',
        'active_subscribers' => 'int',
        'meta' => 'array',
    ];
}
