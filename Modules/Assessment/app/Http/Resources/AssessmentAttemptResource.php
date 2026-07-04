<?php

namespace Modules\Assessment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentAttemptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'assessment_id' => $this->assessment_id,
            'student_id' => $this->student_id,
            'started_at' => $this->started_at?->toIso8601String(),
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'time_taken' => $this->time_taken,
            'score' => (float) $this->score,
            'percentage' => (float) $this->percentage,
            'rank' => $this->rank,
            'status' => $this->status,
            'answers' => $this->whenLoaded('answers', fn () => $this->answers->map(fn ($answer) => [
                'id' => $answer->id,
                'question_id' => $answer->question_id,
                'selected_answer' => $answer->selected_answer,
                'is_correct' => $answer->is_correct,
                'marks_awarded' => (float) $answer->marks_awarded,
            ])->values()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
