<?php

namespace Modules\Tenant\Application\Contracts;

use Illuminate\Support\Collection;
use Modules\Tenant\Domain\Models\Tenant;

interface TenantRepositoryInterface
{
    public function findByDomain(string $domain): ?Tenant;

    public function allActive(): Collection;
}
