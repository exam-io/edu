<?php

namespace Modules\MobileProvisioning\Application\Contracts;

use Illuminate\Support\Collection;
use Modules\MobileProvisioning\Application\DTOs\MobileConfigMutationData;
use Modules\MobileProvisioning\Domain\Models\TenantMobileConfig;

interface MobileConfigServiceInterface
{
    public function current(int $tenantId): TenantMobileConfig;

    public function upsert(int $tenantId, MobileConfigMutationData $data): TenantMobileConfig;

    public function publish(int $tenantId, int $userId): TenantMobileConfig;

    public function requestBuild(int $tenantId, string $platform, int $userId, ?string $notes): int;

    public function requests(int $tenantId): Collection;

    public function invalidateCache(int $tenantId): void;
}
