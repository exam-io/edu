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
            'assessment' => $this->whenLoaded('assessment', fn () => [
                'id' => $this->assessment?->id,
                'title' => $this->assessment?->title,
                'instructions' => $this->assessment?->instructions,
                'start_at' => $this->assessment?->start_at?->toIso8601String(),
                'end_at' => $this->assessment?->end_at?->toIso8601String(),
                'duration_minutes' => $this->assessment?->duration_minutes,
                'randomize_questions' => (bool) ($this->assessment?->randomize_questions ?? false),
                'randomize_options' => (bool) ($this->assessment?->randomize_options ?? false),
                'questions' => $this->assessment?->relationLoaded('questions')
                    ? $this->assessment->questions->map(fn ($question) => [
                        'id' => $question->id,
                        'question_id' => $question->question_id,
                        'marks' => (float) $question->marks,
                        'sort_order' => $question->sort_order,
                        'question' => $question->question ? [
                            'id' => $question->question->id,
                            'stem' => $question->question->stem,
                            'question_type' => $question->question->question_type,
                            'options' => $question->question->options,
                        ] : null,
                    ])->values()
                    : [],
            ]),
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
