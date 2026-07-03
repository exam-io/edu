<?php

namespace Modules\AI\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AIGenerationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('ai.request.create');
    }

    public function rules(): array
    {
        return [
            'content_source_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'generation_type' => ['required', 'string', 'in:questions,notes,summary'],
            'prompt_text' => ['sometimes', 'nullable', 'string', 'max:10000'],
            'options' => ['sometimes', 'array'],
        ];
    }
}
