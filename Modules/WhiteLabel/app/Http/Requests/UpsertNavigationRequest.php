<?php

namespace Modules\WhiteLabel\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertNavigationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('white-label.navigation.manage');
    }

    public function rules(): array
    {
        return [
            'items' => ['required', 'array', 'min:1'],
            'items.*.key' => ['required', 'string', 'max:100'],
            'items.*.label' => ['required', 'string', 'max:120'],
            'items.*.path' => ['required', 'string', 'max:255'],
            'items.*.enabled' => ['required', 'boolean'],
            'items.*.feature' => ['sometimes', 'nullable', 'string', 'max:120'],
        ];
    }
}
