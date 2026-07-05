<?php

namespace Modules\Analytics\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class KpiValue extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'analytics_kpi_values';

    protected $fillable = [
        'tenant_id',
        'kpi_definition_id',
        'kpi_value',
        'period_start',
        'period_end',
        'computed_at',
    ];

    protected function casts(): array
    {
        return [
            'kpi_value' => 'decimal:4',
            'period_start' => 'datetime',
            'period_end' => 'datetime',
            'computed_at' => 'datetime',
        ];
    }

    public function definition(): BelongsTo
    {
        return $this->belongsTo(KpiDefinition::class, 'kpi_definition_id');
    }
}
