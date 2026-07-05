<?php

namespace Modules\MobileProvisioning\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class TenantMobileConfig extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'tenant_mobile_configs';

    protected $fillable = [
        'tenant_id',
        'config',
        'version',
        'published_at',
        'published_by_user_id',
    ];

    protected function casts(): array
    {
        return [
            'config' => 'array',
            'published_at' => 'datetime',
        ];
    }
}
