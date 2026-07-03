<?php

namespace Modules\Teacher\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'employee_no' => ['sometimes', 'string', 'max:80'],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'gender' => ['sometimes', 'string', 'max:32'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:40'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'photo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'qualification' => ['sometimes', 'nullable', 'string', 'max:255'],
            'specialization' => ['sometimes', 'nullable', 'string', 'max:255'],
            'joining_date' => ['sometimes', 'date'],
            'experience_years' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'status' => ['sometimes', 'string', 'max:32'],
            'provision_login_account' => ['sometimes', 'boolean'],
        ];
    }
}
