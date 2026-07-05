<?php

namespace Modules\Reporting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RunReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('reporting.run');
    }

    public function rules(): array
    {
        return [
            'filters' => ['sometimes', 'array'],
        ];
    }
}
