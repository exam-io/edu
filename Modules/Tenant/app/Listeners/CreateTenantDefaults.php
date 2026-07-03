<?php

namespace Modules\Tenant\Listeners;

use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Domain\Enums\TenantStatus;
use Modules\Tenant\Domain\Events\TenantCreated;

class CreateTenantDefaults
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository,
    ) {}

    public function handle(TenantCreated $event): void
    {
        $tenant = $this->tenantRepository->findById($event->tenantId);

        if ($tenant === null) {
            return;
        }

        $tenant->forceFill([
            'status' => $tenant->status ?? TenantStatus::PROVISIONING,
            'plan' => $tenant->plan ?? 'free',
        ])->save();
    }
}
