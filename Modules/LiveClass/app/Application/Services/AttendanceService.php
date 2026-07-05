<?php

namespace Modules\LiveClass\Application\Services;

use Illuminate\Support\Facades\Auth;
use Modules\LiveClass\Application\Contracts\AttendanceServiceInterface;
use Modules\LiveClass\Application\Contracts\LiveClassRepositoryInterface;
use Modules\LiveClass\Domain\Models\LiveClassAttendance;
use Modules\LiveClass\Domain\Models\LiveClassSession;
use Modules\Student\Domain\Models\Student;

class AttendanceService implements AttendanceServiceInterface
{
    public function __construct(
        private readonly LiveClassRepositoryInterface $repository,
    ) {}

    public function join(LiveClassSession $session): LiveClassAttendance
    {
        $tenantId = (int) $session->tenant_id;
        $userId = (int) Auth::id();

        $student = Student::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', $userId)
            ->firstOrFail();

        return $this->repository->upsertAttendance($tenantId, (int) $session->id, (int) $student->id, [
            'joined_at' => now(),
            'attendance_status' => 'joined',
            'source' => 'web',
        ]);
    }
}
