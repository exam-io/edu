<?php

namespace Modules\Audit\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAuditLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('audit.create');
    }

    public function rules(): array
    {
        return [
            'actor_user_id' => ['sometimes', 'nullable', 'integer'],
            'actor_type' => ['sometimes', 'string', 'max:60'],
            'action' => ['required', 'string', 'max:120'],
            'resource_type' => ['sometimes', 'string', 'max:120'],
            'resource_id' => ['sometimes', 'nullable', 'string', 'max:120'],
            'before_state' => ['sometimes', 'array'],
            'after_state' => ['sometimes', 'array'],
            'context' => ['sometimes', 'array'],
        ];
    }
}
