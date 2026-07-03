<?php

namespace Modules\Shared\Infrastructure\Repositories;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Modules\Shared\Application\Contracts\TenantAwareRepositoryInterface;

abstract class AbstractTenantAwareRepository implements TenantAwareRepositoryInterface
{
    public function __construct(protected readonly TenantContextInterface $tenantContext)
    {
    }

    public function tenantId(): ?int
    {
        return $this->tenantContext->tenantId();
    }

    protected function requireTenantId(): int
    {
        $tenantId = $this->tenantId();

        if ($tenantId === null) {
            throw new \RuntimeException('Tenant context is required for tenant-aware repository operations.');
        }

        return $tenantId;
    }
}
