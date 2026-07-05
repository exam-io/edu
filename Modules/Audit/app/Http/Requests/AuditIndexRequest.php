<?php

namespace Modules\Audit\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AuditIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('audit.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'action' => ['sometimes', 'nullable', 'string', 'max:120'],
            'actor' => ['sometimes', 'nullable', 'string', 'max:60'],
        ];
    }
}
