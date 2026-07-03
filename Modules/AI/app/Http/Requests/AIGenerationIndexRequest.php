<?php

namespace Modules\AI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AIGenerationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('ai.request.view');
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:120'],
            'status' => ['sometimes', 'nullable', 'string', 'in:queued,processing,completed,failed'],
            'generation_type' => ['sometimes', 'nullable', 'string', 'in:questions,notes,summary'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
