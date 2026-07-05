<?php

namespace Modules\Communication\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Communication\Application\DTOs\AnnouncementData;
use Modules\Communication\Application\DTOs\CommunicationHistoryQueryData;
use Modules\Communication\Domain\Models\Announcement;

interface CommunicationServiceInterface
{
    public function listAnnouncements(CommunicationHistoryQueryData $query): LengthAwarePaginator;

    public function createAnnouncement(AnnouncementData $data): Announcement;

    public function publishAnnouncement(int $id): Announcement;

    public function listHistory(CommunicationHistoryQueryData $query): LengthAwarePaginator;
}
