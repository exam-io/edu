<?php

namespace Modules\Tenant\Domain\Events;

readonly class TenantSuspended
{
    public function __construct(public int $tenantId) {}
}
