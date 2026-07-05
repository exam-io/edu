<?php

namespace Modules\Subscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionPlanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'billing_interval' => $this->billing_interval,
            'price_amount' => (float) $this->price_amount,
            'currency' => $this->currency,
            'quota' => $this->quota ?? [],
            'is_active' => (bool) $this->is_active,
        ];
    }
}
