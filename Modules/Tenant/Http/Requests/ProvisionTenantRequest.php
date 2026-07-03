<?php

namespace Modules\Tenant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProvisionTenantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('create-tenants');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'lowercase', 'regex:/^[a-z0-9-]+$/', 'max:100', 'unique:tenants'],
            'domain' => ['required', 'string', 'lowercase', 'max:255', 'unique:tenants'],
            'custom_domain' => ['nullable', 'string', 'lowercase', 'max:255', 'unique:tenants'],
            'plan' => ['string', 'in:free,pro,enterprise'],
            'theme' => ['string', 'in:light,dark'],
            'language' => ['string', 'size:2'],
            'timezone' => ['string', 'timezone'],
            'primary_color' => ['regex:/^#[0-9A-Fa-f]{6}$/'],
            'secondary_color' => ['regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.regex' => 'Slug must contain only lowercase letters, numbers, and hyphens.',
            'domain.lowercase' => 'Domain must be lowercase.',
            'primary_color.regex' => 'Primary color must be a valid hex color code.',
            'secondary_color.regex' => 'Secondary color must be a valid hex color code.',
        ];
    }
}
