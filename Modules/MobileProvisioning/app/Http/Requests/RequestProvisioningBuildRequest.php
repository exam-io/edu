<?php

namespace Modules\MobileProvisioning\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RequestProvisioningBuildRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('mobile-provisioning.manage');
    }

    public function rules(): array
    {
        return [
            'platform' => ['required', 'string', 'in:ios,android,both'],
            'notes' => ['sometimes', 'nullable', 'string', 'max:2000'],
        ];
    }
}
