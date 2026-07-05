<?php

namespace Modules\Calendar\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Calendar\Application\DTOs\CalendarEventMutationData;
use Modules\Calendar\Application\DTOs\CalendarEventQueryData;
use Modules\Calendar\Domain\Models\CalendarEvent;

interface CalendarEventServiceInterface
{
    public function list(CalendarEventQueryData $query): LengthAwarePaginator;

    public function find(int $id): CalendarEvent;

    public function create(CalendarEventMutationData $data): CalendarEvent;

    public function update(int $id, CalendarEventMutationData $data): CalendarEvent;

    public function delete(int $id): void;

    public function upsertBySource(int $tenantId, string $sourceType, int $sourceId, array $attributes): CalendarEvent;
}
