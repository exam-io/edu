<?php

namespace Modules\Assessment\Domain\Events;

readonly class AssessmentEvaluated
{
    public function __construct(
        public int $attemptId,
        public int $assessmentId,
        public int $tenantId,
    ) {}
}
