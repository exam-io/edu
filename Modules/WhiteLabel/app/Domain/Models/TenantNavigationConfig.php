<?php

namespace Modules\WhiteLabel\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class TenantNavigationConfig extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'tenant_navigation_configs';

    protected $fillable = [
        'tenant_id',
        'items',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'items' => 'array',
        ];
    }
}
