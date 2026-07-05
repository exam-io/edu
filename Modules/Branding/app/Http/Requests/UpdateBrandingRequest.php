<?php

namespace Modules\Branding\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBrandingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('branding.manage');
    }

    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'nullable', 'string', 'max:120'],
            'logo_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'favicon_url' => ['sometimes', 'nullable', 'url', 'max:2048'],
            'primary_color' => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'accent_color' => ['sometimes', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'font_family' => ['sometimes', 'string', 'max:120'],
            'theme_mode' => ['sometimes', 'string', 'in:light,dark,system'],
            'extra_tokens' => ['sometimes', 'array'],
        ];
    }
}
