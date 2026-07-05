<?php

namespace Modules\Monitoring\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Monitoring\Application\DTOs\MetricsQueryData;
use Modules\Monitoring\Domain\Models\AlertRule;

interface MetricsServiceInterface
{
    public function metrics(int $tenantId, MetricsQueryData $query): LengthAwarePaginator;

    public function aggregateSnapshot(int $tenantId, array $payload): void;

    public function upsertAlertRule(int $tenantId, array $payload): AlertRule;
}
