<?php

namespace Modules\Analytics\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Modules\Analytics\Application\Contracts\AnalyticsRepositoryInterface;
use Modules\Analytics\Application\DTOs\AnalyticsQueryData;
use Modules\Analytics\Domain\Models\AnalyticsEvent;
use Modules\Analytics\Domain\Models\MetricSnapshot;

class AnalyticsRepository implements AnalyticsRepositoryInterface
{
    public function paginateEvents(int $tenantId, AnalyticsQueryData $query): LengthAwarePaginator
    {
        $builder = AnalyticsEvent::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if ($query->q !== null && $query->q !== '') {
            $builder->where(function ($q) use ($query): void {
                $q->where('event_name', 'like', '%' . $query->q . '%')
                    ->orWhere('source_module', 'like', '%' . $query->q . '%')
                    ->orWhere('entity_type', 'like', '%' . $query->q . '%');
            });
        }

        if ($query->eventName !== null && $query->eventName !== '') {
            $builder->where('event_name', $query->eventName);
        }

        return $builder->paginate($query->perPage);
    }

    public function createEvent(array $attributes): AnalyticsEvent
    {
        return AnalyticsEvent::query()->create($attributes);
    }

    public function aggregateEventCounts(int $tenantId, \DateTimeInterface $start, \DateTimeInterface $end): Collection
    {
        return AnalyticsEvent::query()
            ->where('tenant_id', $tenantId)
            ->whereBetween('occurred_at', [$start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s')])
            ->selectRaw('event_name, count(*) as total')
            ->groupBy('event_name')
            ->get();
    }

    public function storeMetricSnapshot(array $attributes): void
    {
        MetricSnapshot::query()->create($attributes);
    }

    public function recentSnapshots(int $tenantId, string $metricKey, int $limit = 30): Collection
    {
        return MetricSnapshot::query()
            ->where('tenant_id', $tenantId)
            ->where('metric_key', $metricKey)
            ->latest('generated_at')
            ->limit($limit)
            ->get();
    }
}
