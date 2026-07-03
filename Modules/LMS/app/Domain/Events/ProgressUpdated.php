<?php

namespace Modules\LMS\Domain\Events;

class ProgressUpdated
{
    public function __construct(
        public readonly int $progressId,
        public readonly int $tenantId,
        public readonly int $courseId,
        public readonly int $studentId,
    ) {}
}
