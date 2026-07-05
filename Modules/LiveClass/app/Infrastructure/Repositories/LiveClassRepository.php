<?php

namespace Modules\LiveClass\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\LiveClass\Application\Contracts\LiveClassRepositoryInterface;
use Modules\LiveClass\Application\DTOs\LiveClassQueryData;
use Modules\LiveClass\Domain\Models\LiveClassAttendance;
use Modules\LiveClass\Domain\Models\LiveClassSession;

class LiveClassRepository implements LiveClassRepositoryInterface
{
    public function paginate(int $tenantId, LiveClassQueryData $query): LengthAwarePaginator
    {
        $builder = LiveClassSession::query()
            ->where('tenant_id', $tenantId)
            ->with(['host:id,name', 'class:id,name', 'section:id,name', 'subject:id,name']);

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->classId !== null) {
            $builder->where('class_id', $query->classId);
        }

        if ($query->sectionId !== null) {
            $builder->where('section_id', $query->sectionId);
        }

        if ($query->search !== null && $query->search !== '') {
            $builder->where(function ($nested) use ($query): void {
                $nested->where('title', 'like', '%' . $query->search . '%')
                    ->orWhere('description', 'like', '%' . $query->search . '%');
            });
        }

        if ($query->fromDate !== null && $query->fromDate !== '') {
            $builder->whereDate('scheduled_start_at', '>=', $query->fromDate);
        }

        if ($query->toDate !== null && $query->toDate !== '') {
            $builder->whereDate('scheduled_end_at', '<=', $query->toDate);
        }

        return $builder
            ->orderBy('scheduled_start_at')
            ->paginate($query->perPage);
    }

    public function findForTenant(int $tenantId, int $id, array $with = []): ?LiveClassSession
    {
        return LiveClassSession::query()
            ->where('tenant_id', $tenantId)
            ->with($with)
            ->find($id);
    }

    public function create(array $attributes): LiveClassSession
    {
        return LiveClassSession::query()->create($attributes);
    }

    public function update(LiveClassSession $session, array $attributes): LiveClassSession
    {
        $session->fill($attributes)->save();

        return $session->refresh();
    }

    public function delete(LiveClassSession $session): void
    {
        $session->delete();
    }

    public function upsertAttendance(int $tenantId, int $liveClassId, int $studentId, array $attributes = []): LiveClassAttendance
    {
        return LiveClassAttendance::query()->updateOrCreate(
            [
                'tenant_id' => $tenantId,
                'live_class_session_id' => $liveClassId,
                'student_id' => $studentId,
            ],
            $attributes,
        );
    }

    public function listAttendance(int $tenantId, int $liveClassId): array
    {
        return LiveClassAttendance::query()
            ->where('tenant_id', $tenantId)
            ->where('live_class_session_id', $liveClassId)
            ->with('student:id,user_id')
            ->latest('joined_at')
            ->get()
            ->all();
    }
}
