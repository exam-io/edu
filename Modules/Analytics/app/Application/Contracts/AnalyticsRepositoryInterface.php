<?php

namespace Modules\Analytics\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Analytics\Application\DTOs\AnalyticsQueryData;
use Modules\Analytics\Domain\Models\AnalyticsEvent;

interface AnalyticsRepositoryInterface
{
    public function paginateEvents(int $tenantId, AnalyticsQueryData $query): LengthAwarePaginator;

    public function createEvent(array $attributes): AnalyticsEvent;

    public function aggregateEventCounts(int $tenantId, \DateTimeInterface $start, \DateTimeInterface $end): Collection;

    public function storeMetricSnapshot(array $attributes): void;

    public function recentSnapshots(int $tenantId, string $metricKey, int $limit = 30): Collection;
}
