<?php

namespace Modules\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('calendar.create');
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string', 'max:4000'],
            'start_at' => ['required', 'date'],
            'end_at' => ['sometimes', 'nullable', 'date', 'after_or_equal:start_at'],
            'all_day' => ['sometimes', 'boolean'],
            'event_type' => ['sometimes', 'string', 'max:64'],
            'status' => ['sometimes', 'string', 'in:scheduled,live,ended,cancelled'],
            'url' => ['sometimes', 'nullable', 'url', 'max:500'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
