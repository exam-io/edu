<?php

namespace Modules\Assessment\Domain\Events;

readonly class ResultGenerated
{
    public function __construct(
        public int $attemptId,
        public int $assessmentId,
        public int $tenantId,
    ) {}
}
