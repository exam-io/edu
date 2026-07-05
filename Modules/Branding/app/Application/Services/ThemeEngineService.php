<?php

namespace Modules\Branding\Application\Services;

use Modules\Branding\Domain\Models\TenantBrandProfile;

class ThemeEngineService
{
    public function resolve(TenantBrandProfile $profile): array
    {
        $base = config('branding.default_branding', []);

        return [
            'theme_mode' => $profile->theme_mode ?? config('branding.default_theme', 'light'),
            'tokens' => array_filter(array_merge($base, [
                'primary_color' => $profile->primary_color,
                'secondary_color' => $profile->secondary_color,
                'accent_color' => $profile->accent_color,
                'font_family' => $profile->font_family,
            ], $profile->extra_tokens ?? []), static fn ($value) => $value !== null),
        ];
    }
}
