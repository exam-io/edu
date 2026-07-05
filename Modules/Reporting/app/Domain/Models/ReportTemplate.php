<?php

namespace Modules\Reporting\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class ReportTemplate extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'slug',
        'source',
        'definition',
        'is_active',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'definition' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function executions(): HasMany
    {
        return $this->hasMany(ReportExecution::class, 'report_template_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ReportSchedule::class, 'report_template_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
