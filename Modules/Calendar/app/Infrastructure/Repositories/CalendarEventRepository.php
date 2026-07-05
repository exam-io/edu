<?php

namespace Modules\Calendar\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Calendar\Application\Contracts\CalendarEventRepositoryInterface;
use Modules\Calendar\Application\DTOs\CalendarEventQueryData;
use Modules\Calendar\Domain\Models\CalendarEvent;

class CalendarEventRepository implements CalendarEventRepositoryInterface
{
    public function paginate(int $tenantId, CalendarEventQueryData $query): LengthAwarePaginator
    {
        $builder = CalendarEvent::query()->where('tenant_id', $tenantId);

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->eventType !== null && $query->eventType !== '') {
            $builder->where('event_type', $query->eventType);
        }

        if ($query->fromDate !== null && $query->fromDate !== '') {
            $builder->whereDate('start_at', '>=', $query->fromDate);
        }

        if ($query->toDate !== null && $query->toDate !== '') {
            $builder->whereDate('start_at', '<=', $query->toDate);
        }

        if ($query->search !== null && $query->search !== '') {
            $builder->where(function ($nested) use ($query): void {
                $nested->where('title', 'like', '%' . $query->search . '%')
                    ->orWhere('description', 'like', '%' . $query->search . '%');
            });
        }

        return $builder
            ->orderBy('start_at')
            ->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id): ?CalendarEvent
    {
        return CalendarEvent::query()
            ->where('tenant_id', $tenantId)
            ->find($id);
    }

    public function create(array $attributes): CalendarEvent
    {
        return CalendarEvent::query()->create($attributes);
    }

    public function update(CalendarEvent $event, array $attributes): CalendarEvent
    {
        $event->fill($attributes)->save();

        return $event->refresh();
    }

    public function delete(CalendarEvent $event): void
    {
        $event->delete();
    }

    public function upsertBySource(int $tenantId, string $sourceType, int $sourceId, array $attributes): CalendarEvent
    {
        return CalendarEvent::query()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'source_type' => $sourceType,
                'source_id' => $sourceId,
            ],
            $attributes,
        );
    }
}
