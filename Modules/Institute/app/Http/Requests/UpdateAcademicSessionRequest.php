<?php

namespace Modules\Institute\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAcademicSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string', 'max:120'],
            'code' => ['sometimes', 'string', 'max:60'],
            'starts_on' => ['sometimes', 'date'],
            'ends_on' => ['sometimes', 'date', 'after:starts_on'],
            'is_current' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'in:planned,active,closed'],
            'metadata' => ['sometimes', 'array'],
        ];
    }
}
