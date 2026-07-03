<?php

namespace Modules\Tenant\Domain\Events;

readonly class TenantCreated
{
    public function __construct(public int $tenantId) {}
}
