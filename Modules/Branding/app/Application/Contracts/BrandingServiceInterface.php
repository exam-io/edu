<?php

namespace Modules\Branding\Application\Contracts;

use Modules\Branding\Application\DTOs\BrandingMutationData;
use Modules\Branding\Domain\Models\TenantBrandProfile;

interface BrandingServiceInterface
{
    public function current(int $tenantId): TenantBrandProfile;

    public function upsert(int $tenantId, BrandingMutationData $data): TenantBrandProfile;

    public function resolveTheme(int $tenantId): array;

    public function invalidateCache(int $tenantId): void;
}
