<?php

namespace Modules\Analytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticsIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('analytics.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:160'],
            'event_name' => ['sometimes', 'nullable', 'string', 'max:120'],
            'metric_key' => ['sometimes', 'nullable', 'string', 'max:120'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
