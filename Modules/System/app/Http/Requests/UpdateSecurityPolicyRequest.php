<?php

namespace Modules\System\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSecurityPolicyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('system.security.manage');
    }

    public function rules(): array
    {
        return [
            'force_mfa' => ['sometimes', 'boolean'],
            'session_ttl_minutes' => ['sometimes', 'integer', 'min:5', 'max:1440'],
            'password_rotation_days' => ['sometimes', 'integer', 'min:1', 'max:365'],
            'allow_ip_restriction' => ['sometimes', 'boolean'],
            'allowed_ip_ranges' => ['sometimes', 'array'],
            'allowed_ip_ranges.*' => ['string', 'max:64'],
            'strict_transport_security' => ['sometimes', 'boolean'],
        ];
    }
}
