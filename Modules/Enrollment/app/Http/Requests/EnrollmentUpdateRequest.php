<?php

namespace Modules\Enrollment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'student_id' => ['sometimes', 'integer', 'min:1'],
            'academic_session_id' => ['sometimes', 'integer', 'min:1'],
            'class_id' => ['sometimes', 'integer', 'min:1'],
            'section_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'batch_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'enrollment_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
