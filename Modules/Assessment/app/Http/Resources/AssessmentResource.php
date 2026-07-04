<?php

namespace Modules\Assessment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssessmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'instructions' => $this->instructions,
            'start_at' => $this->start_at?->toIso8601String(),
            'end_at' => $this->end_at?->toIso8601String(),
            'duration_minutes' => $this->duration_minutes,
            'total_marks' => (float) $this->total_marks,
            'passing_marks' => (float) $this->passing_marks,
            'negative_marking' => (float) $this->negative_marking,
            'randomize_questions' => (bool) $this->randomize_questions,
            'randomize_options' => (bool) $this->randomize_options,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'questions' => $this->whenLoaded('questions', fn () => $this->questions->map(function ($question): array {
                return [
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
                ];
            })->values()),
            'assignments' => $this->whenLoaded('assignments', fn () => $this->assignments->map(function ($assignment): array {
                return [
                    'id' => $assignment->id,
                    'academic_session_id' => $assignment->academic_session_id,
                    'program_id' => $assignment->program_id,
                    'class_id' => $assignment->class_id,
                    'section_id' => $assignment->section_id,
                    'batch_id' => $assignment->batch_id,
                ];
            })->values()),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
