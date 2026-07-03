<?php

namespace Modules\Tenant\Domain\Events;

readonly class TenantResolved
{
    public function __construct(public int $tenantId)
    {
    }
}
