<?php

namespace Modules\SaaS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrackUsageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('saas.usage.manage');
    }

    public function rules(): array
    {
        return [
            'metric_key' => ['required', 'string', 'max:120'],
            'increment_by' => ['sometimes', 'numeric', 'min:0'],
            'period_key' => ['sometimes', 'nullable', 'string', 'max:30'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
