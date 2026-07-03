<?php

namespace Modules\Academic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'program_id' => ['required', 'integer', 'min:1'],
            'academic_session_id' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:60'],
            'description' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
