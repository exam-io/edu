<?php

namespace Modules\Enrollment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EnrollmentStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'integer', 'min:1'],
            'academic_session_id' => ['required', 'integer', 'min:1'],
            'class_id' => ['required', 'integer', 'min:1'],
            'section_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'batch_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'enrollment_date' => ['required', 'date'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
