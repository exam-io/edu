<?php

namespace Modules\Reporting\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class ReportSchedule extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'report_template_id',
        'frequency',
        'next_run_at',
        'is_active',
        'filters',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'next_run_at' => 'datetime',
            'is_active' => 'boolean',
            'filters' => 'array',
        ];
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(ReportTemplate::class, 'report_template_id');
    }
}
