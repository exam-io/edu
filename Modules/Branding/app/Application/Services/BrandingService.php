<?php

namespace Modules\Branding\Application\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Branding\Application\Contracts\BrandingServiceInterface;
use Modules\Branding\Application\DTOs\BrandingMutationData;
use Modules\Branding\Domain\Events\BrandingUpdated;
use Modules\Branding\Domain\Models\TenantBrandProfile;

class BrandingService implements BrandingServiceInterface
{
    public function __construct(
        private readonly ThemeEngineService $themeEngine,
    ) {}

    public function current(int $tenantId): TenantBrandProfile
    {
        return TenantBrandProfile::query()->firstOrCreate(
            ['tenant_id' => $tenantId],
            [
                'name' => null,
                'theme_mode' => config('branding.default_theme', 'light'),
                'primary_color' => config('branding.default_branding.primary_color', '#0b6eff'),
                'secondary_color' => config('branding.default_branding.secondary_color', '#00a889'),
                'accent_color' => config('branding.default_branding.accent_color', '#ff8a00'),
                'font_family' => config('branding.default_branding.font_family', 'Instrument Sans'),
                'extra_tokens' => [],
            ],
        );
    }

    public function upsert(int $tenantId, BrandingMutationData $data): TenantBrandProfile
    {
        $profile = $this->current($tenantId);
        $profile->fill(array_filter($data->toArray(), static fn ($value) => $value !== null));
        $profile->save();

        event(new BrandingUpdated($tenantId));

        return $profile->refresh();
    }

    public function resolveTheme(int $tenantId): array
    {
        $cacheKey = $this->themeCacheKey($tenantId);

        return Cache::remember($cacheKey, (int) config('branding.cache_ttl', 3600), function () use ($tenantId): array {
            $profile = $this->current($tenantId);
            return $this->themeEngine->resolve($profile);
        });
    }

    public function invalidateCache(int $tenantId): void
    {
        Cache::forget($this->themeCacheKey($tenantId));
    }

    private function themeCacheKey(int $tenantId): string
    {
        return "branding:tenant:{$tenantId}:theme";
    }
}
