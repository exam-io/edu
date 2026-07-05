<?php

namespace Modules\CRM\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeadIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('crm.lead.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'in:new,contacted,qualified,converted,lost'],
            'source' => ['sometimes', 'nullable', 'string', 'max:50'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ];
    }
}
