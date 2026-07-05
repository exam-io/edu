<?php

namespace Modules\WhiteLabel\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class TenantDomain extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'tenant_domains';

    protected $fillable = [
        'tenant_id',
        'host',
        'is_primary',
        'status',
        'verification_token',
        'verified_at',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'bool',
            'verified_at' => 'datetime',
        ];
    }
}
