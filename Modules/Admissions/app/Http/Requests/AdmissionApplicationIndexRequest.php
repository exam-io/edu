<?php

namespace Modules\Admissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionApplicationIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('admissions.application.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'in:submitted,under_review,accepted,rejected,waitlisted,enrolled'],
            'program' => ['sometimes', 'nullable', 'string', 'max:190'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
