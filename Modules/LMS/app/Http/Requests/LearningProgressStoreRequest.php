<?php

namespace Modules\LMS\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LearningProgressStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('lms.progress.create');
    }

    public function rules(): array
    {
        return [
            'course_id' => ['required', 'integer', 'min:1'],
            'student_id' => ['required', 'integer', 'min:1'],
            'content_item_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'progress_percent' => ['required', 'integer', 'min:0', 'max:100'],
            'completed_items' => ['sometimes', 'integer', 'min:0'],
            'total_items' => ['sometimes', 'integer', 'min:0'],
            'status' => ['required', 'string', 'in:in_progress,completed,paused,not_started'],
        ];
    }
}
