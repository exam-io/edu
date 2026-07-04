<?php

namespace Modules\Assessment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssessmentManualEvaluateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('submission.evaluate');
    }

    public function rules(): array
    {
        return [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer', 'min:1'],
            'answers.*.marks_awarded' => ['required', 'numeric', 'min:0'],
            'answers.*.is_correct' => ['sometimes', 'nullable', 'boolean'],
        ];
    }
}
