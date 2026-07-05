<?php

namespace Modules\Analytics\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnalyticsTrackEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('analytics.track');
    }

    public function rules(): array
    {
        return [
            'event_name' => ['required', 'string', 'max:120'],
            'source_module' => ['required', 'string', 'max:80'],
            'entity_type' => ['sometimes', 'nullable', 'string', 'max:120'],
            'entity_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'payload' => ['sometimes', 'array'],
            'occurred_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
