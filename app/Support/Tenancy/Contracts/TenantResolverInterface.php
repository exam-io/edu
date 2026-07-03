<?php

namespace App\Support\Tenancy\Contracts;

use Modules\Tenant\Domain\Models\Tenant;

interface TenantResolverInterface
{
    public function resolve(?string $host = null): ?Tenant;
}
