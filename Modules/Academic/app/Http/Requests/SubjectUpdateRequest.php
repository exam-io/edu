<?php

namespace Modules\Academic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'department_id' => ['sometimes', 'integer', 'min:1'],
            'name' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:60'],
            'description' => ['sometimes', 'nullable', 'string'],
            'credit_hours' => ['sometimes', 'integer', 'min:1', 'max:10'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
