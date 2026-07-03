<?php

namespace Modules\Enrollment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherAssignmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'teacher_id' => ['required', 'integer', 'min:1'],
            'academic_session_id' => ['required', 'integer', 'min:1'],
            'class_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'section_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'batch_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'subject_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'start_date' => ['required', 'date'],
            'end_date' => ['sometimes', 'nullable', 'date'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
