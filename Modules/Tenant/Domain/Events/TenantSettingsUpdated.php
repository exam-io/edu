<?php

namespace Modules\Tenant\Domain\Events;

readonly class TenantSettingsUpdated
{
    public function __construct(public int $tenantId) {}
}
