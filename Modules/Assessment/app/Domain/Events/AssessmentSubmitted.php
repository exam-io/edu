<?php

namespace Modules\Assessment\Domain\Events;

readonly class AssessmentSubmitted
{
    public function __construct(
        public int $attemptId,
        public int $assessmentId,
        public int $studentId,
        public int $tenantId,
    ) {}
}
