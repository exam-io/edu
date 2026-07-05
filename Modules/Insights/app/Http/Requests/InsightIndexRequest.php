<?php

namespace Modules\Insights\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InsightIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('insights.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:180'],
            'severity' => ['sometimes', 'nullable', 'string', 'in:low,medium,high,critical'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
