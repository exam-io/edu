<?php

namespace Modules\Admissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionApplicationStatusUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('admissions.application.update');
    }

    public function rules(): array
    {
        return [
            'status' => ['required', 'string', 'in:submitted,under_review,accepted,rejected,waitlisted,enrolled'],
            'note' => ['sometimes', 'nullable', 'string', 'max:1000'],
        ];
    }
}
