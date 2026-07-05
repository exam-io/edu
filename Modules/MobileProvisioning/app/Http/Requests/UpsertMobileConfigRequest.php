<?php

namespace Modules\MobileProvisioning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertMobileConfigRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('mobile-provisioning.manage');
    }

    public function rules(): array
    {
        return [
            'overrides' => ['sometimes', 'array'],
            'min_app_version' => ['sometimes', 'string', 'max:30'],
            'support_email' => ['sometimes', 'email', 'max:190'],
        ];
    }
}
