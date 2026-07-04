<?php

namespace Modules\Assessment\Application\Contracts;

use Modules\Assessment\Domain\Models\AssessmentAttempt;

interface AssessmentEvaluationServiceInterface
{
    public function evaluate(AssessmentAttempt $attempt): AssessmentAttempt;
}
