<?php

namespace Modules\Tenant\Application\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Tenant\Domain\Models\Tenant;

/**
 * Tenant configuration service - manages merged config + tenant overrides.
 */
class TenantConfigurationService
{
    private const CACHE_TTL = 1800; // 30 minutes

    public function getConfiguration(Tenant $tenant): TenantConfigurationDto
    {
        $cacheKey = "tenant:{$tenant->id}:config";

        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($tenant) {
            $settings = $tenant->settings;

            return new TenantConfigurationDto(
                maxUsers: config('tenancy.plans.' . $tenant->plan . '.max_users', 100),
                maxStorage: config('tenancy.plans.' . $tenant->plan . '.max_storage', 5),
                features: config('tenancy.plans.' . $tenant->plan . '.features', []),
                customDomain: $tenant->custom_domain,
                primaryDomain: $tenant->domain,
                language: $settings?->language ?? config('app.locale'),
                timezone: $settings?->timezone ?? config('app.timezone'),
                theme: $settings?->theme ?? 'light',
            );
        });
    }

    public function invalidateConfigCache(Tenant $tenant): void
    {
        $cacheKey = "tenant:{$tenant->id}:config";
        Cache::forget($cacheKey);
    }
}

/**
 * Data transfer object for tenant configuration.
 */
readonly class TenantConfigurationDto
{
    public function __construct(
        public int $maxUsers,
        public int $maxStorage,
        public array $features,
        public ?string $customDomain,
        public string $primaryDomain,
        public string $language,
        public string $timezone,
        public string $theme,
    ) {}

    public function toArray(): array
    {
        return [
            'max_users' => $this->maxUsers,
            'max_storage' => $this->maxStorage,
            'features' => $this->features,
            'custom_domain' => $this->customDomain,
            'primary_domain' => $this->primaryDomain,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'theme' => $this->theme,
        ];
    }
}
