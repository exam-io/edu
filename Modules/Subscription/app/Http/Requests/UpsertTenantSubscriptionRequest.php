<?php

namespace Modules\Subscription\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpsertTenantSubscriptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('subscription.manage');
    }

    public function rules(): array
    {
        return [
            'plan_id' => ['required', 'integer', 'exists:subscription_plans,id'],
            'status' => ['sometimes', 'string', 'in:trialing,active,past_due,canceled,expired'],
            'starts_at' => ['sometimes', 'nullable', 'date'],
            'renews_at' => ['sometimes', 'nullable', 'date'],
            'ends_at' => ['sometimes', 'nullable', 'date'],
            'meta' => ['sometimes', 'array'],
        ];
    }
}
