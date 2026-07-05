<?php

namespace Modules\Subscription\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('subscription.plan.manage');
    }

    public function rules(): array
    {
        return [
            'id' => ['sometimes', 'integer', 'exists:subscription_plans,id'],
            'code' => ['required', 'string', 'max:40'],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['sometimes', 'nullable', 'string', 'max:400'],
            'billing_interval' => ['sometimes', 'string', 'in:monthly,quarterly,yearly'],
            'price_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['sometimes', 'string', 'size:3'],
            'quota' => ['sometimes', 'array'],
            'is_active' => ['sometimes', 'boolean'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
