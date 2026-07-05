<?php

namespace Modules\MobileProvisioning\Application\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Branding\Application\Contracts\BrandingServiceInterface;
use Modules\FeatureManagement\Application\Contracts\FeatureFlagServiceInterface;
use Modules\MobileProvisioning\Application\Contracts\MobileConfigServiceInterface;
use Modules\MobileProvisioning\Application\DTOs\MobileConfigMutationData;
use Modules\MobileProvisioning\Domain\Events\MobileConfigPublished;
use Modules\MobileProvisioning\Domain\Events\MobileProvisioningRequested;
use Modules\MobileProvisioning\Domain\Models\MobileProvisioningRequest;
use Modules\MobileProvisioning\Domain\Models\TenantMobileConfig;
use Modules\WhiteLabel\Application\Contracts\NavigationServiceInterface;

class MobileConfigService implements MobileConfigServiceInterface
{
    public function __construct(
        private readonly BrandingServiceInterface $brandingService,
        private readonly NavigationServiceInterface $navigationService,
        private readonly FeatureFlagServiceInterface $featureFlagService,
    ) {}

    public function current(int $tenantId): TenantMobileConfig
    {
        return Cache::remember($this->cacheKey($tenantId), (int) config('mobileprovisioning.cache_ttl', 1200), function () use ($tenantId) {
            return TenantMobileConfig::query()->firstOrCreate(
                ['tenant_id' => $tenantId],
                [
                    'version' => 1,
                    'config' => $this->buildConfig($tenantId, []),
                ],
            );
        });
    }

    public function upsert(int $tenantId, MobileConfigMutationData $data): TenantMobileConfig
    {
        $model = TenantMobileConfig::query()->firstOrCreate(['tenant_id' => $tenantId]);

        $overrides = array_filter([
            'min_app_version' => $data->minAppVersion,
            'support_email' => $data->supportEmail,
        ], static fn ($value) => $value !== null);

        $model->config = $this->buildConfig($tenantId, array_merge($overrides, $data->overrides));
        $model->version = ((int) $model->version) + 1;
        $model->save();

        $this->invalidateCache($tenantId);

        return $model->refresh();
    }

    public function publish(int $tenantId, int $userId): TenantMobileConfig
    {
        $model = $this->current($tenantId);
        $model->published_at = now();
        $model->published_by_user_id = $userId;
        $model->save();

        event(new MobileConfigPublished($tenantId));

        return $model->refresh();
    }

    public function requestBuild(int $tenantId, string $platform, int $userId, ?string $notes): int
    {
        $request = MobileProvisioningRequest::query()->create([
            'tenant_id' => $tenantId,
            'platform' => $platform,
            'status' => 'queued',
            'requested_by_user_id' => $userId,
            'notes' => $notes,
            'requested_at' => now(),
        ]);

        event(new MobileProvisioningRequested($tenantId, $request->id));

        return $request->id;
    }

    public function requests(int $tenantId): Collection
    {
        return MobileProvisioningRequest::query()
            ->where('tenant_id', $tenantId)
            ->latest('id')
            ->get();
    }

    public function invalidateCache(int $tenantId): void
    {
        Cache::forget($this->cacheKey($tenantId));
    }

    private function cacheKey(int $tenantId): string
    {
        return "mobile-provisioning:tenant:{$tenantId}:config";
    }

    private function buildConfig(int $tenantId, array $overrides): array
    {
        $branding = $this->brandingService->resolveTheme($tenantId);
        $navigation = $this->navigationService->current($tenantId);
        $featureFlags = $this->featureFlagService->flags($tenantId)->map(fn ($flag) => [
            'feature_key' => $flag->feature_key,
            'enabled' => (bool) $flag->enabled,
        ])->values()->all();

        return array_merge([
            'min_app_version' => config('mobileprovisioning.defaults.min_app_version', '1.0.0'),
            'support_email' => config('mobileprovisioning.defaults.support_email', 'support@eduos.app'),
            'branding' => $branding,
            'navigation' => $navigation->items ?? [],
            'features' => $featureFlags,
        ], $overrides);
    }
}
