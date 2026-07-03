<?php

namespace Modules\Institute\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstituteBrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'logo_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'primary_color' => ['sometimes', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['sometimes', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'tagline' => ['sometimes', 'nullable', 'string', 'max:150'],
        ];
    }
}
