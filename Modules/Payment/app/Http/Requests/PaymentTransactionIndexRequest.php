<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentTransactionIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('payment.transaction.view');
    }

    public function rules(): array
    {
        return [
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'status' => ['sometimes', 'nullable', 'string', 'max:40'],
            'provider' => ['sometimes', 'nullable', 'string', 'in:null,stripe,paypal'],
            'search' => ['sometimes', 'nullable', 'string', 'max:120'],
        ];
    }
}
