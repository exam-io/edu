<?php

namespace Modules\Branding\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Shared\Domain\Models\TenantAwareModel;

class TenantBrandProfile extends TenantAwareModel
{
    use HasFactory;

    protected $table = 'tenant_brand_profiles';

    protected $fillable = [
        'tenant_id',
        'name',
        'logo_url',
        'favicon_url',
        'primary_color',
        'secondary_color',
        'accent_color',
        'font_family',
        'theme_mode',
        'extra_tokens',
    ];

    protected function casts(): array
    {
        return [
            'extra_tokens' => 'array',
        ];
    }
}
