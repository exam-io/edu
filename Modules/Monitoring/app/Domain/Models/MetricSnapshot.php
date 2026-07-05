<?php

namespace Modules\Monitoring\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MetricSnapshot extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'metric_key',
        'period_key',
        'value',
        'meta',
    ];

    protected $casts = [
        'value' => 'float',
        'meta' => 'array',
    ];
}
