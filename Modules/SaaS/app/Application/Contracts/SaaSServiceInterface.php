<?php

namespace Modules\SaaS\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\SaaS\Application\DTOs\TrackUsageData;
use Modules\SaaS\Application\DTOs\UsageQueryData;
use Modules\SaaS\Domain\Models\UsageCounter;
use Modules\SaaS\Domain\Models\UsageSnapshot;

interface SaaSServiceInterface
{
    public function dashboard(int $tenantId): array;

    public function usage(int $tenantId, UsageQueryData $query): LengthAwarePaginator;

    public function trackUsage(int $tenantId, TrackUsageData $data): UsageCounter;

    public function requestSnapshot(int $tenantId, ?string $snapshotDate = null): UsageSnapshot;
}
