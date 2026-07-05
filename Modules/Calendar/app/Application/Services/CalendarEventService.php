<?php

namespace Modules\Calendar\Application\Services;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Calendar\Application\Contracts\CalendarEventRepositoryInterface;
use Modules\Calendar\Application\Contracts\CalendarEventServiceInterface;
use Modules\Calendar\Application\DTOs\CalendarEventMutationData;
use Modules\Calendar\Application\DTOs\CalendarEventQueryData;
use Modules\Calendar\Domain\Models\CalendarEvent;

class CalendarEventService implements CalendarEventServiceInterface
{
    public function __construct(
        private readonly CalendarEventRepositoryInterface $repository,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function list(CalendarEventQueryData $query): LengthAwarePaginator
    {
        $tenantId = $this->tenantId();

        return $this->repository->paginate($tenantId, $query);
    }

    public function find(int $id): CalendarEvent
    {
        $tenantId = $this->tenantId();
        $event = $this->repository->findForTenant($tenantId, $id);

        abort_if($event === null, 404, 'Calendar event not found.');

        return $event;
    }

    public function create(CalendarEventMutationData $data): CalendarEvent
    {
        return $this->repository->create(array_merge($data->toArray(), [
            'tenant_id' => $this->tenantId(),
        ]));
    }

    public function update(int $id, CalendarEventMutationData $data): CalendarEvent
    {
        return $this->repository->update($this->find($id), $data->toArray());
    }

    public function delete(int $id): void
    {
        $this->repository->delete($this->find($id));
    }

    public function upsertBySource(int $tenantId, string $sourceType, int $sourceId, array $attributes): CalendarEvent
    {
        return $this->repository->upsertBySource($tenantId, $sourceType, $sourceId, $attributes);
    }

    private function tenantId(): int
    {
        $tenantId = $this->tenantContext->tenantId();
        abort_if($tenantId === null, 422, 'Tenant context is required.');

        return (int) $tenantId;
    }
}
