<?php

namespace Modules\Tenant\Application\Services;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Modules\Tenant\Domain\Models\Tenant;

/**
 * Tenant context service - façade for accessing and managing tenant context.
 */
class TenantContextService
{
    public function __construct(
        private readonly TenantContextInterface $context,
    ) {}

    public function tenant(): ?Tenant
    {
        return $this->context->tenant();
    }

    public function tenantId(): ?int
    {
        return $this->context->tenantId();
    }

    public function hasTenant(): bool
    {
        return $this->context->hasTenant();
    }

    public function setTenant(?Tenant $tenant): void
    {
        $this->context->setTenant($tenant);
    }

    /**
     * Get tenant ID or throw if not set.
     */
    public function requiredTenantId(): int
    {
        $id = $this->tenantId();
        if ($id === null) {
            throw new \RuntimeException('Tenant context not set.');
        }
        return $id;
    }

    /**
     * Get tenant or throw if not set.
     */
    public function requiredTenant(): Tenant
    {
        $tenant = $this->tenant();
        if ($tenant === null) {
            throw new \RuntimeException('Tenant context not set.');
        }
        return $tenant;
    }
}
