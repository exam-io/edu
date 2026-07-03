<?php

namespace Modules\Course\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('course.update');
    }

    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'string', 'max:255'],
            'code' => ['sometimes', 'string', 'max:60'],
            'description' => ['sometimes', 'nullable', 'string', 'max:5000'],
            'level' => ['sometimes', 'string', 'in:beginner,intermediate,advanced'],
            'duration_minutes' => ['sometimes', 'integer', 'min:0', 'max:100000'],
            'price' => ['sometimes', 'numeric', 'min:0', 'max:99999999.99'],
            'status' => ['sometimes', 'string', 'in:draft,published,archived'],
            'published_at' => ['sometimes', 'nullable', 'date'],
        ];
    }
}
