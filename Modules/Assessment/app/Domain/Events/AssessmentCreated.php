<?php

namespace Modules\Assessment\Domain\Events;

readonly class AssessmentCreated
{
    public function __construct(
        public int $assessmentId,
        public int $tenantId,
    ) {}
}
