<?php

namespace Modules\Assessment\Application\Contracts;

use Modules\Assessment\Domain\Models\AssessmentAttempt;

interface RankingServiceInterface
{
    public function assignRank(AssessmentAttempt $attempt): AssessmentAttempt;
}
