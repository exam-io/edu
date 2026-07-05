<?php

namespace Modules\Payment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CapturePaymentIntentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('payment.intent.manage');
    }

    public function rules(): array
    {
        return [
            'amount' => ['sometimes', 'nullable', 'numeric', 'min:0.01'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
