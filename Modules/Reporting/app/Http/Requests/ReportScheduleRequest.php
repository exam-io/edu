<?php

namespace Modules\Reporting\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('reporting.schedule');
    }

    public function rules(): array
    {
        return [
            'report_template_id' => ['required', 'integer', 'min:1'],
            'frequency' => ['required', 'string', 'in:daily,hourly,weekly'],
            'next_run_at' => ['required', 'date'],
            'filters' => ['sometimes', 'array'],
        ];
    }
}
