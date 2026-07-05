<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentIntentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('payment.intent.manage');
    }

    public function rules(): array
    {
        return [
            'invoice_id' => ['sometimes', 'nullable', 'integer', 'exists:invoices,id'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'provider' => ['sometimes', 'nullable', 'string', 'in:null,stripe,paypal'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
