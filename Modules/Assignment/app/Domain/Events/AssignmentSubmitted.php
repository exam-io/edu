<?php

namespace Modules\Assignment\Domain\Events;

readonly class AssignmentSubmitted
{
    public function __construct(
        public int $submissionId,
        public int $assessmentId,
        public int $studentId,
        public int $tenantId,
    ) {}
}
