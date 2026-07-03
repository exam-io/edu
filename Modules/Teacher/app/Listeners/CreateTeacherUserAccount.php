<?php

namespace Modules\Teacher\Listeners;

use Modules\Teacher\Application\Contracts\TeacherProvisioningServiceInterface;
use Modules\Teacher\Domain\Events\TeacherCreated;
use Modules\Teacher\Domain\Models\Teacher;

class CreateTeacherUserAccount
{
    public function __construct(private readonly TeacherProvisioningServiceInterface $provisioningService)
    {
    }

    public function handle(TeacherCreated $event): void
    {
        if (! $event->provisionLoginAccount) {
            return;
        }

        $teacher = Teacher::query()->find($event->teacherId);
        if ($teacher === null) {
            return;
        }

        $this->provisioningService->provisionTeacherUser($teacher);
    }
}
