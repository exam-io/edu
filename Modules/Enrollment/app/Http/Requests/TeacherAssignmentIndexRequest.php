<?php

namespace Modules\Enrollment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherAssignmentIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:120'],
            'status' => ['sometimes', 'nullable', 'string', 'max:32'],
            'teacher_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'academic_session_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
