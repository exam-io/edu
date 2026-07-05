<?php

namespace Modules\LiveClass\Application\Services;

use App\Support\Tenancy\Contracts\TenantContextInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\LiveClass\Application\Contracts\AttendanceServiceInterface;
use Modules\LiveClass\Application\Contracts\JitsiProviderInterface;
use Modules\LiveClass\Application\Contracts\LiveClassRepositoryInterface;
use Modules\LiveClass\Application\Contracts\LiveClassServiceInterface;
use Modules\LiveClass\Application\DTOs\LiveClassMutationData;
use Modules\LiveClass\Application\DTOs\LiveClassQueryData;
use Modules\LiveClass\Domain\Events\LiveClassEnded;
use Modules\LiveClass\Domain\Events\LiveClassJoined;
use Modules\LiveClass\Domain\Events\LiveClassScheduled;
use Modules\LiveClass\Domain\Events\LiveClassStarted;
use Modules\LiveClass\Domain\Models\LiveClassAttendance;
use Modules\LiveClass\Domain\Models\LiveClassSession;

class LiveClassService implements LiveClassServiceInterface
{
    public function __construct(
        private readonly LiveClassRepositoryInterface $repository,
        private readonly JitsiProviderInterface $jitsi,
        private readonly AttendanceServiceInterface $attendanceService,
        private readonly TenantContextInterface $tenantContext,
    ) {}

    public function list(LiveClassQueryData $query): LengthAwarePaginator
    {
        $tenantId = $this->tenantId();

        return $this->repository->paginate($tenantId, $query);
    }

    public function find(int $id): LiveClassSession
    {
        $tenantId = $this->tenantId();
        $session = $this->repository->findForTenant($tenantId, $id, ['host:id,name', 'attendances']);

        abort_if($session === null, 404, 'Live class not found.');

        return $session;
    }

    public function create(LiveClassMutationData $data): LiveClassSession
    {
        return DB::transaction(function () use ($data): LiveClassSession {
            $tenantId = $this->tenantId();
            $meeting = $this->jitsi->buildMeeting([
                'tenant_id' => $tenantId,
                'title' => $data->title,
            ]);

            $session = $this->repository->create(array_merge($data->toArray(), [
                'tenant_id' => $tenantId,
                'provider' => $meeting['provider'],
                'provider_meeting_id' => $meeting['provider_meeting_id'],
                'room_name' => $meeting['room_name'],
                'meeting_url' => $meeting['meeting_url'],
                'meeting_password' => $meeting['meeting_password'],
                'status' => 'scheduled',
                'meta' => array_merge($data->meta, $meeting['meta']),
            ]));

            event(new LiveClassScheduled((int) $session->id, $tenantId));

            return $session;
        });
    }

    public function update(int $id, LiveClassMutationData $data): LiveClassSession
    {
        $session = $this->find($id);

        if (in_array($session->status, ['live', 'ended'], true)) {
            abort(422, 'Cannot update a live/ended class.');
        }

        return $this->repository->update($session, $data->toArray());
    }

    public function delete(int $id): void
    {
        $session = $this->find($id);

        if ($session->status === 'live') {
            abort(422, 'Cannot delete a class while live.');
        }

        $this->repository->delete($session);
    }

    public function start(int $id): LiveClassSession
    {
        return DB::transaction(function () use ($id): LiveClassSession {
            $session = $this->find($id);

            if ($session->status !== 'scheduled') {
                abort(422, 'Live class is not in scheduled state.');
            }

            $updated = $this->repository->update($session, [
                'status' => 'live',
                'actual_start_at' => now(),
            ]);

            event(new LiveClassStarted((int) $updated->id, (int) $updated->tenant_id));

            return $updated;
        });
    }

    public function end(int $id): LiveClassSession
    {
        return DB::transaction(function () use ($id): LiveClassSession {
            $session = $this->find($id);

            if ($session->status !== 'live') {
                abort(422, 'Live class is not currently live.');
            }

            $updated = $this->repository->update($session, [
                'status' => 'ended',
                'actual_end_at' => now(),
            ]);

            event(new LiveClassEnded((int) $updated->id, (int) $updated->tenant_id));

            return $updated;
        });
    }

    public function attendance(int $id): array
    {
        $session = $this->find($id);

        return $this->repository->listAttendance((int) $session->tenant_id, (int) $session->id);
    }

    public function join(int $id): LiveClassSession
    {
        $session = $this->find($id);

        if ($session->status !== 'live') {
            abort(422, 'Class is not live yet.');
        }

        $attendance = $this->attendanceService->join($session);

        event(new LiveClassJoined((int) $session->id, (int) $attendance->student_id, (int) $session->tenant_id));

        return $session->refresh();
    }

    private function tenantId(): int
    {
        $tenantId = $this->tenantContext->tenantId();
        abort_if($tenantId === null, 422, 'Tenant context is required.');

        return (int) $tenantId;
    }
}
