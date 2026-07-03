<?php

namespace Modules\Shared\Application\Contracts;

interface TenantAwareRepositoryInterface
{
    public function tenantId(): ?int;
}
