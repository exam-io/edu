<?php

namespace Modules\Enrollment\Domain\Events;

class TeacherAssigned
{
    public function __construct(
        public readonly int $assignmentId,
        public readonly int $teacherId,
        public readonly int $tenantId,
    ) {
    }
}
