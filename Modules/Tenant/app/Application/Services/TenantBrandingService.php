<?php

namespace Modules\Tenant\Application\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Tenant\Domain\Models\Tenant;
use Modules\Tenant\Domain\Models\TenantSetting;

/**
 * Tenant branding service - loads and caches branding data.
 */
class TenantBrandingService
{
    private const CACHE_TTL = 3600; // 1 hour

    public function getBranding(Tenant $tenant): TenantBrandingDto
    {
        $cacheKey = "tenant:{$tenant->id}:branding";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($tenant) {
            $settings = $tenant->settings ?? new TenantSetting();

            return new TenantBrandingDto(
                primaryColor: $settings->primary_color ?? '#0b6eff',
                secondaryColor: $settings->secondary_color ?? '#00a889',
                logo: $settings->logo,
                favicon: $settings->favicon,
                theme: $settings->theme ?? 'light',
                language: $settings->language ?? 'en',
                timezone: $settings->timezone ?? 'UTC',
            );
        });
    }

    public function invalidateBrandingCache(Tenant $tenant): void
    {
        $cacheKey = "tenant:{$tenant->id}:branding";
        Cache::forget($cacheKey);
    }
}

/**
 * Data transfer object for tenant branding.
 */
readonly class TenantBrandingDto
{
    public function __construct(
        public string $primaryColor,
        public string $secondaryColor,
        public ?string $logo,
        public ?string $favicon,
        public string $theme,
        public string $language,
        public string $timezone,
    ) {}

    public function toArray(): array
    {
        return [
            'primary_color' => $this->primaryColor,
            'secondary_color' => $this->secondaryColor,
            'logo' => $this->logo,
            'favicon' => $this->favicon,
            'theme' => $this->theme,
            'language' => $this->language,
            'timezone' => $this->timezone,
        ];
    }
}
