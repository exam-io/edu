<?php

namespace Modules\Tenant\Listeners;

use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Application\Services\TenantProvisioningService;
use Modules\Tenant\Domain\Events\TenantCreated;

class PrepareTenantStorage
{
    public function __construct(
        private readonly TenantProvisioningService $provisioningService,
        private readonly TenantRepositoryInterface $tenantRepository,
    ) {}

    public function handle(TenantCreated $event): void
    {
        $tenant = $this->tenantRepository->findById($event->tenantId);

        if ($tenant === null) {
            return;
        }

        $this->provisioningService->initializeStorage($tenant);
    }
}
