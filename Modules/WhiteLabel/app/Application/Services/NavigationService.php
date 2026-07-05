<?php

namespace Modules\WhiteLabel\Application\Services;

use Illuminate\Support\Facades\Cache;
use Modules\WhiteLabel\Application\Contracts\NavigationServiceInterface;
use Modules\WhiteLabel\Application\DTOs\NavigationMutationData;
use Modules\WhiteLabel\Domain\Events\NavigationConfigurationUpdated;
use Modules\WhiteLabel\Domain\Models\TenantNavigationConfig;

class NavigationService implements NavigationServiceInterface
{
    public function current(int $tenantId): TenantNavigationConfig
    {
        $cacheKey = $this->cacheKey($tenantId);

        return Cache::remember($cacheKey, (int) config('whitelabel.cache_ttl', 1800), function () use ($tenantId) {
            return TenantNavigationConfig::query()->firstOrCreate(
                ['tenant_id' => $tenantId],
                [
                    'items' => config('whitelabel.default_navigation', []),
                    'version' => 1,
                ],
            );
        });
    }

    public function upsert(int $tenantId, NavigationMutationData $data): TenantNavigationConfig
    {
        $config = TenantNavigationConfig::query()->firstOrCreate(['tenant_id' => $tenantId]);
        $config->items = $data->items;
        $config->version = ((int) $config->version) + 1;
        $config->save();

        event(new NavigationConfigurationUpdated($tenantId));

        return $config->refresh();
    }

    public function invalidateCache(int $tenantId): void
    {
        Cache::forget($this->cacheKey($tenantId));
    }

    private function cacheKey(int $tenantId): string
    {
        return "whitelabel:tenant:{$tenantId}:navigation";
    }
}
