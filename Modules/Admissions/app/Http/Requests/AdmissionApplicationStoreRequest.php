<?php

namespace Modules\Admissions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionApplicationStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('admissions.application.create');
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['sometimes', 'nullable', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:190'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'lead_id' => ['sometimes', 'nullable', 'integer', 'exists:crm_leads,id'],
            'workflow_id' => ['sometimes', 'nullable', 'integer', 'exists:admission_workflows,id'],
            'program' => ['sometimes', 'nullable', 'string', 'max:190'],
            'source' => ['sometimes', 'nullable', 'string', 'max:100'],
            'status' => ['sometimes', 'string', 'in:submitted,under_review,accepted,rejected,waitlisted,enrolled'],
            'notes' => ['sometimes', 'nullable', 'string'],
        ];
    }
}
