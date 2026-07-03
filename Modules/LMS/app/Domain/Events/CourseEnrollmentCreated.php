<?php

namespace Modules\LMS\Domain\Events;

class CourseEnrollmentCreated
{
    public function __construct(
        public readonly int $enrollmentId,
        public readonly int $tenantId,
        public readonly int $courseId,
        public readonly int $studentId,
    ) {}
}
