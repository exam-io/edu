<?php

namespace Modules\WhiteLabel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDomainRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('white-label.domain.manage');
    }

    public function rules(): array
    {
        return [
            'host' => ['required', 'string', 'max:255'],
            'is_primary' => ['sometimes', 'boolean'],
            'status' => ['sometimes', 'string', 'in:pending_verification,verified,disabled'],
        ];
    }
}
