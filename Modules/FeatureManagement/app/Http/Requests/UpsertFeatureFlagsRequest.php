<?php

namespace Modules\FeatureManagement\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertFeatureFlagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('feature-management.manage');
    }

    public function rules(): array
    {
        return [
            'flags' => ['required', 'array'],
            'flags.*.feature_key' => ['required', 'string', 'max:120'],
            'flags.*.enabled' => ['required', 'boolean'],
            'flags.*.source' => ['sometimes', 'string', 'max:40'],
        ];
    }
}
