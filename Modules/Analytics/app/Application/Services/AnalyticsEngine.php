<?php

namespace Modules\Analytics\Application\Services;

use Illuminate\Support\Facades\Event;
use Modules\Analytics\Application\Contracts\AnalyticsEngineInterface;
use Modules\Analytics\Application\Contracts\AnalyticsRepositoryInterface;
use Modules\Analytics\Domain\Events\MetricsAggregated;

class AnalyticsEngine implements AnalyticsEngineInterface
{
    public function __construct(
        private readonly AnalyticsRepositoryInterface $repository,
    ) {}

    public function aggregateForTenant(int $tenantId, \DateTimeInterface $start, \DateTimeInterface $end): void
    {
        $rows = $this->repository->aggregateEventCounts($tenantId, $start, $end);

        foreach ($rows as $row) {
            $this->repository->storeMetricSnapshot([
                'tenant_id' => $tenantId,
                'metric_key' => 'events.count',
                'dimension_key' => 'event_name',
                'dimension_value' => (string) $row->event_name,
                'metric_value' => (float) $row->total,
                'period_start' => $start->format('Y-m-d H:i:s'),
                'period_end' => $end->format('Y-m-d H:i:s'),
                'generated_at' => now(),
            ]);
        }

        Event::dispatch(new MetricsAggregated(
            tenantId: $tenantId,
            periodStart: $start->format(DATE_ATOM),
            periodEnd: $end->format(DATE_ATOM),
        ));
    }
}
