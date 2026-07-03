<?php

namespace Modules\Tenant\Listeners;

use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Application\Services\TenantBrandingService;
use Modules\Tenant\Application\Services\TenantConfigurationService;
use Modules\Tenant\Domain\Events\TenantSettingsUpdated;

class InvalidateTenantCaches
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository,
        private readonly TenantBrandingService $brandingService,
        private readonly TenantConfigurationService $configurationService,
    ) {}

    public function handle(TenantSettingsUpdated $event): void
    {
        $tenant = $this->tenantRepository->findById($event->tenantId);

        if ($tenant === null) {
            return;
        }

        $this->brandingService->invalidateBrandingCache($tenant);
        $this->configurationService->invalidateConfigCache($tenant);
    }
}
