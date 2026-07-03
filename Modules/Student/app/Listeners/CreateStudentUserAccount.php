<?php

namespace Modules\Student\Listeners;

use Modules\Student\Application\Contracts\StudentProvisioningServiceInterface;
use Modules\Student\Domain\Events\StudentCreated;
use Modules\Student\Domain\Models\Student;

class CreateStudentUserAccount
{
    public function __construct(private readonly StudentProvisioningServiceInterface $provisioningService)
    {
    }

    public function handle(StudentCreated $event): void
    {
        if (! $event->provisionLoginAccount) {
            return;
        }

        $student = Student::query()->find($event->studentId);
        if ($student === null) {
            return;
        }

        $this->provisioningService->provisionStudentUser($student);
    }
}
