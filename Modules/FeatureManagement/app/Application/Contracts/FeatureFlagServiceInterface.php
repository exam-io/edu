<?php

namespace Modules\FeatureManagement\Application\Contracts;

use Illuminate\Support\Collection;
use Modules\FeatureManagement\Application\DTOs\FeatureFlagMutationData;

interface FeatureFlagServiceInterface
{
    public function catalog(): Collection;

    public function flags(int $tenantId): Collection;

    public function upsert(int $tenantId, FeatureFlagMutationData $data): Collection;

    public function evaluate(int $tenantId, string $featureKey): bool;

    public function invalidateCache(int $tenantId): void;
}
