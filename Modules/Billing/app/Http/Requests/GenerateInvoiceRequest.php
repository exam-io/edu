<?php

namespace Modules\Billing\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GenerateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('billing.invoice.manage');
    }

    public function rules(): array
    {
        return [
            'currency' => ['sometimes', 'string', 'size:3'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'due_at' => ['sometimes', 'nullable', 'date'],
            'line_items' => ['required', 'array', 'min:1'],
            'line_items.*.description' => ['required', 'string', 'max:255'],
            'line_items.*.quantity' => ['required', 'integer', 'min:1'],
            'line_items.*.unit_amount' => ['required', 'numeric', 'min:0'],
            'line_items.*.tax_amount' => ['sometimes', 'numeric', 'min:0'],
            'line_items.*.usage_key' => ['sometimes', 'nullable', 'string', 'max:120'],
            'line_items.*.usage_count' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'line_items.*.meta' => ['sometimes', 'array'],
        ];
    }
}
