<?php

namespace Modules\Assessment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentStoreRequest extends FormRequest
{
    private const TYPES = [
        'Assignment',
        'Quiz',
        'Practice Test',
        'Mock Test',
        'Unit Test',
        'Mid-Term',
        'Final Exam',
        'Entrance Exam',
        'Homework',
    ];

    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('assessment.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'type' => ['required', 'string', 'in:' . implode(',', self::TYPES)],
            'instructions' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'start_at' => ['sometimes', 'nullable', 'date'],
            'end_at' => ['sometimes', 'nullable', 'date', 'after:start_at'],
            'duration_minutes' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'total_marks' => ['sometimes', 'numeric', 'min:0'],
            'passing_marks' => ['sometimes', 'numeric', 'min:0'],
            'negative_marking' => ['sometimes', 'numeric', 'min:0'],
            'randomize_questions' => ['sometimes', 'boolean'],
            'randomize_options' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string', 'in:draft,published,archived'],
            'questions' => ['sometimes', 'array'],
            'questions.*.question_id' => ['required_with:questions', 'integer', 'min:1'],
            'questions.*.marks' => ['sometimes', 'numeric', 'min:0'],
            'questions.*.sort_order' => ['sometimes', 'integer', 'min:1'],
            'assignments' => ['sometimes', 'array'],
            'assignments.*.academic_session_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'assignments.*.program_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'assignments.*.class_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'assignments.*.section_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'assignments.*.batch_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
        ];
    }
}
