<?php

namespace Modules\Analytics\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class MetricSnapshot extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'analytics_metric_snapshots';

    protected $fillable = [
        'tenant_id',
        'metric_key',
        'dimension_key',
        'dimension_value',
        'metric_value',
        'period_start',
        'period_end',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'metric_value' => 'decimal:4',
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'generated_at' => 'datetime',
        ];
    }
}
