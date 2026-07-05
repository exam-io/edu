<?php

namespace Modules\Calendar\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Calendar\Application\DTOs\CalendarEventQueryData;
use Modules\Calendar\Domain\Models\CalendarEvent;

interface CalendarEventRepositoryInterface
{
    public function paginate(int $tenantId, CalendarEventQueryData $query): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id): ?CalendarEvent;

    public function create(array $attributes): CalendarEvent;

    public function update(CalendarEvent $event, array $attributes): CalendarEvent;

    public function delete(CalendarEvent $event): void;

    public function upsertBySource(int $tenantId, string $sourceType, int $sourceId, array $attributes): CalendarEvent;
}
