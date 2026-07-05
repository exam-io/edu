<?php

namespace Modules\Reporting\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class ReportExport extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'report_execution_id',
        'format',
        'file_path',
        'status',
        'requested_by',
    ];

    public function execution(): BelongsTo
    {
        return $this->belongsTo(ReportExecution::class, 'report_execution_id');
    }
}
