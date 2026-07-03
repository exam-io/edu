<?php

namespace Modules\LMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LearningProgressUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('lms.progress.update');
    }

    public function rules(): array
    {
        return [
            'content_item_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'progress_percent' => ['sometimes', 'integer', 'min:0', 'max:100'],
            'completed_items' => ['sometimes', 'integer', 'min:0'],
            'total_items' => ['sometimes', 'integer', 'min:0'],
            'status' => ['sometimes', 'string', 'in:in_progress,completed,paused,not_started'],
        ];
    }
}
