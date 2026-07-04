<?php

namespace Modules\Assessment\Application\Contracts;

use Modules\Assessment\Domain\Models\AssessmentAttempt;

interface ResultServiceInterface
{
    public function buildResult(AssessmentAttempt $attempt): array;
}
