<?php

namespace Modules\Tenant\Domain\Events;

readonly class TenantActivated
{
    public function __construct(public int $tenantId) {}
}
