<?php

namespace Modules\Calendar\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CalendarIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('calendar.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'string', 'in:scheduled,live,ended,cancelled'],
            'event_type' => ['sometimes', 'nullable', 'string', 'max:64'],
            'from_date' => ['sometimes', 'nullable', 'date'],
            'to_date' => ['sometimes', 'nullable', 'date', 'after_or_equal:from_date'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
