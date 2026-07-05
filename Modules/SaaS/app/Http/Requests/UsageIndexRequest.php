<?php

namespace Modules\SaaS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsageIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('saas.usage.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'metric_key' => ['sometimes', 'nullable', 'string', 'max:120'],
            'period_key' => ['sometimes', 'nullable', 'string', 'max:30'],
        ];
    }
}
