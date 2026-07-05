<?php

namespace Modules\LiveClass\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LiveClassStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('live_class.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'host_user_id' => ['required', 'integer', 'exists:users,id'],
            'class_id' => ['sometimes', 'nullable', 'integer', 'exists:classes,id'],
            'section_id' => ['sometimes', 'nullable', 'integer', 'exists:sections,id'],
            'subject_id' => ['sometimes', 'nullable', 'integer', 'exists:subjects,id'],
            'scheduled_start_at' => ['required', 'date'],
            'scheduled_end_at' => ['required', 'date', 'after:scheduled_start_at'],
            'max_participants' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'attendance_policy' => ['sometimes', 'string', 'in:open,enrolled_only,invite_only'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
