<?php

namespace Modules\Parent\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name' => ['sometimes', 'string', 'max:255'],
            'relationship' => ['sometimes', 'string', 'max:60'],
            'phone' => ['sometimes', 'string', 'max:40'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'occupation' => ['sometimes', 'nullable', 'string', 'max:255'],
            'address' => ['sometimes', 'nullable', 'string'],
            'status' => ['sometimes', 'string', 'max:32'],
            'provision_login_account' => ['sometimes', 'boolean'],
        ];
    }
}
