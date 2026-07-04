<?php

namespace Modules\Assessment\Domain\Events;

readonly class AssessmentPublished
{
    public function __construct(
        public int $assessmentId,
        public int $tenantId,
    ) {}
}
