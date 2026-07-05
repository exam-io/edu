<?php

namespace Modules\Notification\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDeviceTokenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'token' => ['required', 'string', 'max:500'],
            'device_type' => ['sometimes', 'string', 'in:web,android,ios'],
            'is_active' => ['sometimes', 'boolean'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
