<?php

namespace Modules\Academic\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SectionStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'class_id' => ['required', 'integer', 'min:1'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:60'],
            'capacity' => ['required', 'integer', 'min:1'],
            'status' => ['sometimes', 'string', 'max:32'],
        ];
    }
}
