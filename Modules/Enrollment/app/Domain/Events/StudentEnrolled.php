<?php

namespace Modules\Enrollment\Domain\Events;

class StudentEnrolled
{
    public function __construct(
        public readonly int $enrollmentId,
        public readonly int $studentId,
        public readonly int $tenantId,
    ) {
    }
}
