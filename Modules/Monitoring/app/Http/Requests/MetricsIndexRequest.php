<?php

namespace Modules\Monitoring\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MetricsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('monitoring.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'metric_key' => ['sometimes', 'nullable', 'string', 'max:120'],
            'period_key' => ['sometimes', 'nullable', 'string', 'max:40'],
        ];
    }
}
