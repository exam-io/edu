<?php

namespace Modules\Assessment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResultResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'attempt_id' => $this['attempt_id'] ?? null,
            'assessment_id' => $this['assessment_id'] ?? null,
            'assessment_title' => $this['assessment_title'] ?? null,
            'score' => $this['score'] ?? 0,
            'percentage' => $this['percentage'] ?? 0,
            'rank' => $this['rank'] ?? null,
            'status' => $this['status'] ?? null,
            'started_at' => $this['started_at'] ?? null,
            'submitted_at' => $this['submitted_at'] ?? null,
            'time_taken' => $this['time_taken'] ?? null,
            'total_marks' => $this['total_marks'] ?? 0,
            'passing_marks' => $this['passing_marks'] ?? 0,
            'question_count' => $this['question_count'] ?? 0,
        ];
    }
}
