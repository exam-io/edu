<?php

namespace App\Support\Tenancy\Isolation;

use App\Support\Tenancy\Contracts\CacheIsolationInterface;
use App\Support\Tenancy\Contracts\TenantContextInterface;

class TenantCacheIsolation implements CacheIsolationInterface
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function key(string $key): string
    {
        $tenantId = $this->tenantContext->tenantId() ?? 'system';

        return "tenant:{$tenantId}:{$key}";
    }
}
