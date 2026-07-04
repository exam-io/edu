<?php

namespace Modules\Assessment\Domain\Events;

readonly class AssessmentStarted
{
    public function __construct(
        public int $attemptId,
        public int $assessmentId,
        public int $studentId,
        public int $tenantId,
    ) {}
}
