<?php

namespace Modules\Communication\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Communication\Application\Contracts\CommunicationRepositoryInterface;
use Modules\Communication\Domain\Models\Announcement;
use Modules\Communication\Domain\Models\CommunicationHistory;

class CommunicationRepository implements CommunicationRepositoryInterface
{
    public function paginateAnnouncements(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $builder = Announcement::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if (! empty($filters['status'])) {
            $builder->where('status', (string) $filters['status']);
        }

        if (! empty($filters['search'])) {
            $q = (string) $filters['search'];
            $builder->where(function ($query) use ($q): void {
                $query->where('title', 'like', '%' . $q . '%')
                    ->orWhere('body', 'like', '%' . $q . '%');
            });
        }

        return $builder->paginate($perPage);
    }

    public function findAnnouncementForTenant(int $tenantId, int $id): ?Announcement
    {
        return Announcement::query()->where('tenant_id', $tenantId)->find($id);
    }

    public function createAnnouncement(array $attributes): Announcement
    {
        return Announcement::query()->create($attributes);
    }

    public function updateAnnouncement(Announcement $announcement, array $attributes): Announcement
    {
        $announcement->fill($attributes)->save();

        return $announcement->refresh();
    }

    public function paginateHistory(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $builder = CommunicationHistory::query()
            ->where('tenant_id', $tenantId)
            ->latest('id');

        if (! empty($filters['status'])) {
            $builder->where('status', (string) $filters['status']);
        }

        if (! empty($filters['channel'])) {
            $builder->where('channel', (string) $filters['channel']);
        }

        if (! empty($filters['search'])) {
            $q = (string) $filters['search'];
            $builder->where(function ($query) use ($q): void {
                $query->where('subject', 'like', '%' . $q . '%')
                    ->orWhere('content', 'like', '%' . $q . '%');
            });
        }

        return $builder->paginate($perPage);
    }

    public function createHistory(array $attributes): CommunicationHistory
    {
        return CommunicationHistory::query()->create($attributes);
    }
}
