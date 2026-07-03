<?php

namespace Modules\Content\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContentItemStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('content.item.create');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'min:1'],
            'course_section_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'media_asset_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'title' => ['required', 'string', 'max:255'],
            'content_type' => ['required', 'string', 'in:text,video,quiz,file,assignment'],
            'content_body' => ['sometimes', 'nullable', 'string', 'max:65535'],
            'duration_seconds' => ['sometimes', 'integer', 'min:0', 'max:86400'],
            'sort_order' => ['sometimes', 'integer', 'min:0', 'max:100000'],
            'is_required' => ['sometimes', 'boolean'],
            'status' => ['required', 'string', 'in:draft,published,archived'],
            'published_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
