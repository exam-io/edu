<?php

namespace Modules\Academic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'department_id' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:60'],
            'description' => ['sometimes', 'nullable', 'string'],
            'credit_hours' => ['required', 'integer', 'min:1', 'max:10'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
