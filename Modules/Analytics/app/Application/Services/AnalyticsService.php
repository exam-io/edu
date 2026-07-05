<?php

namespace Modules\Analytics\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Event;
use Modules\Analytics\Application\Contracts\AnalyticsRepositoryInterface;
use Modules\Analytics\Application\Contracts\AnalyticsServiceInterface;
use Modules\Analytics\Application\DTOs\AnalyticsQueryData;
use Modules\Analytics\Application\DTOs\TrackEventData;
use Modules\Analytics\Domain\Events\AnalyticsEventTracked;
use Modules\Analytics\Domain\Models\AnalyticsEvent;
use Modules\Tenant\Application\Services\TenantContextService;

class AnalyticsService implements AnalyticsServiceInterface
{
    public function __construct(
        private readonly AnalyticsRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function listEvents(AnalyticsQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginateEvents($this->tenantId(), $query);
    }

    public function track(TrackEventData $data): AnalyticsEvent
    {
        $tenantId = $data->tenantId ?? $this->tenantId();

        $event = $this->repository->createEvent([
            'tenant_id' => $tenantId,
            'event_name' => $data->eventName,
            'source_module' => $data->sourceModule,
            'entity_type' => $data->entityType,
            'entity_id' => $data->entityId,
            'payload' => $data->payload,
            'occurred_at' => $data->occurredAt ?? now(),
            'created_by' => auth()->id(),
        ]);

        Event::dispatch(new AnalyticsEventTracked($event->id, $event->tenant_id));

        return $event;
    }

    public function metricSeries(string $metricKey): Collection
    {
        return $this->repository->recentSnapshots($this->tenantId(), $metricKey)
            ->sortBy('generated_at')
            ->values();
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
