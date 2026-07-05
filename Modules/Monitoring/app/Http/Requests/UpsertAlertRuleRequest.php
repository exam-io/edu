<?php

namespace Modules\Monitoring\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertAlertRuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('monitoring.alert.manage');
    }

    public function rules(): array
    {
        return [
            'id' => ['sometimes', 'integer', 'exists:alert_rules,id'],
            'metric_key' => ['required', 'string', 'max:120'],
            'operator' => ['sometimes', 'string', 'in:>,>=,<,<=,='],
            'threshold' => ['required', 'numeric'],
            'severity' => ['sometimes', 'string', 'in:info,warning,critical'],
            'is_active' => ['sometimes', 'boolean'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
