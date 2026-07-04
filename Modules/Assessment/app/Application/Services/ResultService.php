<?php

namespace Modules\Assessment\Application\Services;

use Modules\Assessment\Application\Contracts\ResultServiceInterface;
use Modules\Assessment\Domain\Models\AssessmentAttempt;

class ResultService implements ResultServiceInterface
{
    public function buildResult(AssessmentAttempt $attempt): array
    {
        $assessment = $attempt->assessment()->with('questions.question')->firstOrFail();

        return [
            'attempt_id' => $attempt->id,
            'assessment_id' => $assessment->id,
            'assessment_title' => $assessment->title,
            'score' => (float) $attempt->score,
            'percentage' => (float) $attempt->percentage,
            'rank' => $attempt->rank,
            'status' => $attempt->status,
            'started_at' => $attempt->started_at?->toIso8601String(),
            'submitted_at' => $attempt->submitted_at?->toIso8601String(),
            'time_taken' => $attempt->time_taken,
            'total_marks' => (float) $assessment->total_marks,
            'passing_marks' => (float) $assessment->passing_marks,
            'question_count' => $assessment->questions->count(),
        ];
    }
}
