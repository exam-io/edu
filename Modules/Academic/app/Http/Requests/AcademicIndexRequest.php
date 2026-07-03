<?php

namespace Modules\Academic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcademicIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'max:32'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'department_id' => ['sometimes', 'integer', 'min:1'],
            'program_id' => ['sometimes', 'integer', 'min:1'],
            'class_id' => ['sometimes', 'integer', 'min:1'],
            'academic_session_id' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
