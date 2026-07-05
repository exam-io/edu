<?php

namespace Modules\Reporting\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class ReportExecution extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'report_template_id',
        'status',
        'filters',
        'result_payload',
        'row_count',
        'started_at',
        'completed_at',
        'requested_by',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'result_payload' => 'array',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }

    public function exports(): HasMany
    {
        return $this->hasMany(ReportExport::class, 'report_execution_id');
    }
}
