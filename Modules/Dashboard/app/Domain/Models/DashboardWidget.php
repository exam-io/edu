<?php

namespace Modules\Dashboard\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class DashboardWidget extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'dashboard_definition_id',
        'widget_key',
        'title',
        'sort_order',
        'config',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
        ];
    }

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(DashboardDefinition::class, 'dashboard_definition_id');
    }
}
