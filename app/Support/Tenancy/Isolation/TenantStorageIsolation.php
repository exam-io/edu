<?php

namespace App\Support\Tenancy\Isolation;

use App\Support\Tenancy\Contracts\StorageIsolationInterface;
use App\Support\Tenancy\Contracts\TenantContextInterface;

class TenantStorageIsolation implements StorageIsolationInterface
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function tenantPath(string $path = ''): string
    {
        $tenantId = $this->tenantContext->tenantId() ?? 'system';

        return trim("tenants/{$tenantId}/{$path}", '/');
    }
}
