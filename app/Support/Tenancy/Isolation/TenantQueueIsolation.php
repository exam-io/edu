<?php

namespace App\Support\Tenancy\Isolation;

use App\Support\Tenancy\Contracts\QueueIsolationInterface;
use App\Support\Tenancy\Contracts\TenantContextInterface;

class TenantQueueIsolation implements QueueIsolationInterface
{
    public function __construct(private readonly TenantContextInterface $tenantContext)
    {
    }

    public function queue(string $queue): string
    {
        $tenantId = $this->tenantContext->tenantId() ?? 'system';

        return "tenant-{$tenantId}-{$queue}";
    }
}
