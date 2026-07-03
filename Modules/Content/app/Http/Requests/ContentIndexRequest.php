<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'q' => ['sometimes', 'nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'in:draft,published,archived'],
            'course_id' => ['sometimes', 'integer', 'min:1'],
            'course_section_id' => ['sometimes', 'integer', 'min:1'],
            'content_type' => ['sometimes', 'nullable', 'string', 'in:text,video,quiz,file,assignment'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
