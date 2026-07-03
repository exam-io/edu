<?php

namespace Modules\Tenant\Listeners;

use Illuminate\Support\Facades\Log;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Application\Services\TenantProvisioningService;
use Modules\Tenant\Domain\Events\TenantCreated;

class InitializeTenantStorage
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

        try {
            $this->provisioningService->initializeStorage($tenant);
        } catch (\Throwable $exception) {
            Log::warning('Failed to initialize tenant storage.', [
                'tenant_id' => $event->tenantId,
                'error' => $exception->getMessage(),
            ]);
        }
    }
}
