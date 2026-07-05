<?php

namespace Modules\Dashboard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DashboardPreferenceUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('dashboard.configure');
    }

    public function rules(): array
    {
        return [
            'dashboard_definition_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'preferences' => ['required', 'array'],
        ];
    }
}
