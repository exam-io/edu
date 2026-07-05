<?php

namespace Modules\Communication\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Communication\Application\Contracts\CommunicationRepositoryInterface;
use Modules\Communication\Domain\Events\AnnouncementPublished;
use Modules\Communication\Jobs\SendCommunicationJob;

class QueueAnnouncementDispatch implements ShouldQueue
{
    public function __construct(
        private readonly CommunicationRepositoryInterface $repository,
    ) {}

    public function handle(AnnouncementPublished $event): void
    {
        $announcement = $this->repository->findAnnouncementForTenant($event->tenantId, $event->announcementId);
        if ($announcement === null) {
            return;
        }

        $targetUserIds = is_array($announcement->target_user_ids)
            ? array_values(array_map('intval', $announcement->target_user_ids))
            : [];

        foreach ($targetUserIds as $userId) {
            SendCommunicationJob::dispatch(
                tenantId: $event->tenantId,
                sourceType: 'announcement',
                sourceId: $announcement->id,
                userId: $userId,
                subject: $announcement->title,
                content: $announcement->body,
                channels: is_array($announcement->channels) ? $announcement->channels : ['in_app'],
            );
        }
    }
}
