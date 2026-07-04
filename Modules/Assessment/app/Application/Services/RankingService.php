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

        foreach ($leaderboard as $entry) {
            AssessmentAttempt::query()
                ->where('tenant_id', $attempt->tenant_id)
                ->whereKey((int) $entry->id)
                ->update(['rank' => $rank]);

            $rank++;
        }

        return $attempt->refresh();
    }
}
