<?php

namespace Modules\FeatureManagement\Application\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\FeatureManagement\Application\Contracts\FeatureFlagServiceInterface;
use Modules\FeatureManagement\Application\DTOs\FeatureFlagMutationData;
use Modules\FeatureManagement\Domain\Events\FeatureFlagsChanged;
use Modules\FeatureManagement\Domain\Models\FeatureCatalog;
use Modules\FeatureManagement\Domain\Models\TenantFeatureFlag;

class FeatureFlagService implements FeatureFlagServiceInterface
{
    public function catalog(): Collection
    {
        return FeatureCatalog::query()->orderBy('key')->get();
    }

    public function flags(int $tenantId): Collection
    {
        return Cache::remember($this->cacheKey($tenantId), (int) config('featuremanagement.cache_ttl', 1800), function () use ($tenantId) {
            return TenantFeatureFlag::query()->where('tenant_id', $tenantId)->orderBy('feature_key')->get();
        });
    }

    public function upsert(int $tenantId, FeatureFlagMutationData $data): Collection
    {
        foreach ($data->flags as $flag) {
            $featureKey = isset($flag['feature_key']) ? (string) $flag['feature_key'] : null;
            if ($featureKey === null || $featureKey === '') {
                continue;
            }

            TenantFeatureFlag::query()->updateOrCreate(
                ['tenant_id' => $tenantId, 'feature_key' => $featureKey],
                [
                    'enabled' => (bool) ($flag['enabled'] ?? false),
                    'source' => (string) ($flag['source'] ?? 'manual'),
                ],
            );
        }

        event(new FeatureFlagsChanged($tenantId));

        return $this->flags($tenantId);
    }

    public function evaluate(int $tenantId, string $featureKey): bool
    {
        $flag = $this->flags($tenantId)->firstWhere('feature_key', $featureKey);
        if ($flag !== null) {
            return (bool) $flag->enabled;
        }

        $catalog = FeatureCatalog::query()->where('key', $featureKey)->first();
        if ($catalog !== null) {
            return (bool) $catalog->enabled_by_default;
        }

        return in_array($featureKey, config('tenancy.plans.free.features', []), true);
    }

    public function invalidateCache(int $tenantId): void
    {
        Cache::forget($this->cacheKey($tenantId));
    }

    private function cacheKey(int $tenantId): string
    {
        return "feature-management:tenant:{$tenantId}:flags";
    }
}
