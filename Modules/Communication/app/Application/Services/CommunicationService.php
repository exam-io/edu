<?php

namespace Modules\Communication\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Modules\Communication\Application\Contracts\CommunicationRepositoryInterface;
use Modules\Communication\Application\Contracts\CommunicationServiceInterface;
use Modules\Communication\Application\DTOs\AnnouncementData;
use Modules\Communication\Application\DTOs\CommunicationHistoryQueryData;
use Modules\Communication\Domain\Events\AnnouncementPublished;
use Modules\Communication\Domain\Models\Announcement;
use Modules\Tenant\Application\Services\TenantContextService;

class CommunicationService implements CommunicationServiceInterface
{
    public function __construct(
        private readonly CommunicationRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function listAnnouncements(CommunicationHistoryQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginateAnnouncements($this->tenantId(), $query->perPage, [
            'status' => $query->status,
            'search' => $query->search,
        ]);
    }

    public function createAnnouncement(AnnouncementData $data): Announcement
    {
        return $this->repository->createAnnouncement(array_merge($data->toArray(), [
            'tenant_id' => $this->tenantId(),
        ]));
    }

    public function publishAnnouncement(int $id): Announcement
    {
        $announcement = $this->repository->findAnnouncementForTenant($this->tenantId(), $id);
        abort_if($announcement === null, 404, 'Announcement not found.');

        $published = $this->repository->updateAnnouncement($announcement, [
            'status' => 'published',
            'published_at' => now(),
        ]);

        Event::dispatch(new AnnouncementPublished($published->id, $published->tenant_id));

        return $published;
    }

    public function listHistory(CommunicationHistoryQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginateHistory($this->tenantId(), $query->perPage, [
            'status' => $query->status,
            'channel' => $query->channel,
            'search' => $query->search,
        ]);
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
