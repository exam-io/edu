<?php

namespace Modules\Analytics\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class KpiDefinition extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'analytics_kpi_definitions';

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'description',
        'formula',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'formula' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function values(): HasMany
    {
        return $this->hasMany(KpiValue::class, 'kpi_definition_id');
    }
}
