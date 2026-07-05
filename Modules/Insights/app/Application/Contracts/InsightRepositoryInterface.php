<?php

namespace Modules\Insights\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Insights\Application\DTOs\InsightQueryData;

interface InsightRepositoryInterface
{
    public function paginateGenerated(int $tenantId, InsightQueryData $query): LengthAwarePaginator;

    public function activeRules(int $tenantId): Collection;

    public function latestMetricValue(int $tenantId, string $metricKey): ?float;

    public function createGenerated(array $attributes): \Modules\Insights\Domain\Models\GeneratedInsight;
}
