<?php

namespace Modules\Assessment\Application\Contracts;

use Modules\Assessment\Domain\Models\Assessment;

interface AssessmentAssignmentServiceInterface
{
    public function syncAssignments(Assessment $assessment, array $assignments): Assessment;
}
