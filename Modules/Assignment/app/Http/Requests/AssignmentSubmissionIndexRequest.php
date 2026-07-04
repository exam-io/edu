<?php

namespace Modules\Assignment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentSubmissionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('submission.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:120'],
            'status' => ['sometimes', 'nullable', 'string', 'max:32'],
            'assessment_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'student_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
