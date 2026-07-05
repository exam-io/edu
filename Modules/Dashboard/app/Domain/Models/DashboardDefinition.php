<?php

namespace Modules\Dashboard\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class DashboardDefinition extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'role_key',
        'is_default',
        'layout',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
            'layout' => 'array',
        ];
    }

    public function widgets(): HasMany
    {
        return $this->hasMany(DashboardWidget::class, 'dashboard_definition_id')->orderBy('sort_order');
    }
}
