<?php

namespace Modules\Assessment\Application\Contracts;

use Modules\Assessment\Domain\Models\Assessment;

interface AssessmentBuilderServiceInterface
{
    public function syncQuestions(Assessment $assessment, array $questions): Assessment;
}
