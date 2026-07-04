<?php

namespace Modules\Assessment\Application\Services;

use Modules\Assessment\Application\Contracts\AssessmentRepositoryInterface;
use Modules\Assessment\Application\Contracts\RankingServiceInterface;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

class RankingService implements RankingServiceInterface
{
    public function __construct(private readonly AssessmentRepositoryInterface $repository)
    {
    }

    public function assignRank(AssessmentAttempt $attempt): AssessmentAttempt
    {
        $leaderboard = $this->repository->fetchLeaderboard($attempt->tenant_id, $attempt->assessment_id);
        $rank = 1;
        $rankByAttemptId = [];

        foreach ($leaderboard as $entry) {
            $rankByAttemptId[(int) $entry->id] = $rank;
            $rank++;
        }

        $this->repository->updateRanks($attempt->tenant_id, $rankByAttemptId);

        return $attempt->refresh();
    }
}
