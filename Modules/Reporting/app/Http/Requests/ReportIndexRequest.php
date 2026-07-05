<?php

namespace Modules\Reporting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('reporting.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:160'],
            'status' => ['sometimes', 'nullable', 'string', 'in:active,inactive'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
