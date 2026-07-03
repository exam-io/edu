<?php

namespace Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTenantSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'theme' => ['sometimes', 'string', 'in:light,dark'],
            'language' => ['sometimes', 'string', 'size:2'],
            'timezone' => ['sometimes', 'string', 'timezone'],
            'primary_color' => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo' => ['sometimes', 'nullable', 'string', 'max:255'],
            'favicon' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
