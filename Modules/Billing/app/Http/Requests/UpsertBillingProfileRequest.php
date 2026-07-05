<?php

namespace Modules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertBillingProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('billing.manage');
    }

    public function rules(): array
    {
        return [
            'legal_name' => ['sometimes', 'nullable', 'string', 'max:190'],
            'email' => ['sometimes', 'nullable', 'email', 'max:190'],
            'phone' => ['sometimes', 'nullable', 'string', 'max:60'],
            'country' => ['sometimes', 'nullable', 'string', 'max:80'],
            'city' => ['sometimes', 'nullable', 'string', 'max:80'],
            'address_line' => ['sometimes', 'nullable', 'string', 'max:255'],
            'postal_code' => ['sometimes', 'nullable', 'string', 'max:30'],
            'tax_id' => ['sometimes', 'nullable', 'string', 'max:80'],
            'currency' => ['sometimes', 'nullable', 'string', 'size:3'],
        ];
    }
}
