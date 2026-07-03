<?php

namespace App\Support\Tenancy;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Modules\Tenant\Domain\Models\Tenant;

class TenantContext implements TenantContextInterface
{
    private ?Tenant $tenant = null;

    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function tenant(): ?Tenant
    {
        return $this->tenant;
    }

    public function tenantId(): ?int
    {
        return $this->tenant?->id;
    }

    public function hasTenant(): bool
    {
        return $this->tenant !== null;
    }
}
