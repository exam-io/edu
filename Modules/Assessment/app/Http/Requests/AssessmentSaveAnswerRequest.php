<?php

namespace Modules\Assessment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentSaveAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('assessment_attempt.update');
    }

    public function rules(): array
    {
        return [
            'question_id' => ['required', 'integer', 'min:1'],
            'selected_answer' => ['sometimes', 'array'],
            'mark_for_review' => ['sometimes', 'boolean'],
        ];
    }
}
