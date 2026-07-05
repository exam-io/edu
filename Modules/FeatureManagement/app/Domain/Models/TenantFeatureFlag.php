<?php

namespace Modules\FeatureManagement\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class TenantFeatureFlag extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'tenant_feature_flags';

    protected $fillable = [
        'tenant_id',
        'feature_key',
        'enabled',
        'source',
    ];

    protected function casts(): array
    {
        return ['enabled' => 'bool'];
    }
}
