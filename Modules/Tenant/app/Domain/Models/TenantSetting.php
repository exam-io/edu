<?php

namespace Modules\Tenant\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantSetting extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'tenant_settings';

    protected $fillable = [
        'tenant_id',
        'theme',
        'language',
        'timezone',
        'logo',
        'favicon',
        'primary_color',
        'secondary_color',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
