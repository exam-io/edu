<?php

namespace Modules\Insights\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class GeneratedInsight extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'insight_rule_id',
        'title',
        'summary',
        'severity',
        'context_payload',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'context_payload' => 'array',
            'generated_at' => 'datetime',
        ];
    }

    public function rule(): BelongsTo
    {
        return $this->belongsTo(InsightRule::class, 'insight_rule_id');
    }
}
