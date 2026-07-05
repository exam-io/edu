<?php

namespace Modules\WhiteLabel\Application\Contracts;

use Modules\WhiteLabel\Application\DTOs\NavigationMutationData;
use Modules\WhiteLabel\Domain\Models\TenantNavigationConfig;

interface NavigationServiceInterface
{
    public function current(int $tenantId): TenantNavigationConfig;

    public function upsert(int $tenantId, NavigationMutationData $data): TenantNavigationConfig;

    public function invalidateCache(int $tenantId): void;
}
