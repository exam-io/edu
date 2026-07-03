<?php

namespace App\Support\Tenancy\Resolvers;

use App\Support\Tenancy\Contracts\TenantResolverInterface;
use Modules\Tenant\Domain\Models\Tenant;

class DomainTenantResolver implements TenantResolverInterface
{
    public function resolve(?string $host = null): ?Tenant
    {
        if ($host === null || $host === '') {
            return null;
        }

        return Tenant::query()
            ->where('domain', $host)
            ->orWhere('slug', $host)
            ->first();
    }
}
