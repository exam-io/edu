<?php

namespace Modules\Student\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'admission_no' => ['sometimes', 'string', 'max:80'],
            'roll_no' => ['sometimes', 'nullable', 'string', 'max:80'],
            'first_name' => ['sometimes', 'string', 'max:255'],
            'middle_name' => ['sometimes', 'nullable', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'gender' => ['sometimes', 'string', 'max:32'],
            'date_of_birth' => ['sometimes', 'date'],
            'blood_group' => ['sometimes', 'nullable', 'string', 'max:16'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:40'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'photo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string'],
            'city' => ['sometimes', 'nullable', 'string', 'max:255'],
            'state' => ['sometimes', 'nullable', 'string', 'max:255'],
            'country' => ['sometimes', 'nullable', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'nullable', 'string', 'max:20'],
            'emergency_contact' => ['sometimes', 'nullable', 'string', 'max:100'],
            'admission_date' => ['sometimes', 'date'],
            'status' => ['sometimes', 'string', 'max:32'],
            'parent_ids' => ['sometimes', 'array'],
            'parent_ids.*' => ['integer', 'min:1'],
            'primary_parent_id' => ['sometimes', 'nullable', 'integer', 'min:1'],
            'provision_login_account' => ['sometimes', 'boolean'],
        ];
    }
}
