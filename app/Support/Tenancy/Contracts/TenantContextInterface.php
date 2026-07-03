<?php

namespace App\Support\Tenancy\Contracts;

use Modules\Tenant\Domain\Models\Tenant;

interface TenantContextInterface
{
    public function setTenant(?Tenant $tenant): void;

    public function tenant(): ?Tenant;

    public function tenantId(): ?int;

    public function hasTenant(): bool;
}
