<?php

namespace Modules\Monitoring\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AlertRule extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'metric_key',
        'operator',
        'threshold',
        'severity',
        'is_active',
        'meta',
    ];

    protected $casts = [
        'threshold' => 'float',
        'is_active' => 'bool',
        'meta' => 'array',
    ];
}
