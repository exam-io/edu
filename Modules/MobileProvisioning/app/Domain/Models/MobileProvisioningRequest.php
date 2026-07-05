<?php

namespace Modules\MobileProvisioning\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class MobileProvisioningRequest extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'mobile_provisioning_requests';

    protected $fillable = [
        'tenant_id',
        'platform',
        'status',
        'requested_by_user_id',
        'notes',
        'requested_at',
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
        ];
    }
}
