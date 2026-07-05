<?php

namespace Modules\Reporting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('reporting.create');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:180'],
            'slug' => ['required', 'string', 'max:180'],
            'source' => ['required', 'string', 'max:120'],
            'definition' => ['required', 'array'],
            'filters' => ['sometimes', 'array'],
        ];
    }
}
