<?php

namespace Modules\Insights\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class InsightRule extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'metric_key',
        'operator',
        'threshold',
        'severity',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'threshold' => 'decimal:4',
            'is_active' => 'boolean',
        ];
    }

    public function generatedInsights(): HasMany
    {
        return $this->hasMany(GeneratedInsight::class, 'insight_rule_id');
    }
}
