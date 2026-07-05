<?php

namespace Modules\Dashboard\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Shared\Domain\Models\TenantAwareModel;

class UserDashboardPreference extends TenantAwareModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'dashboard_definition_id',
        'preferences',
    ];

    protected function casts(): array
    {
        return [
            'preferences' => 'array',
        ];
    }

    public function dashboard(): BelongsTo
    {
        return $this->belongsTo(DashboardDefinition::class, 'dashboard_definition_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }
}
