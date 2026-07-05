<?php

namespace Modules\Communication\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Communication\Domain\Models\Announcement;
use Modules\Communication\Domain\Models\CommunicationHistory;

interface CommunicationRepositoryInterface
{
    public function paginateAnnouncements(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator;

    public function findAnnouncementForTenant(int $tenantId, int $id): ?Announcement;

    public function createAnnouncement(array $attributes): Announcement;

    public function updateAnnouncement(Announcement $announcement, array $attributes): Announcement;

    public function paginateHistory(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator;

    public function createHistory(array $attributes): CommunicationHistory;
}
