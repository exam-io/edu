<?php

namespace Modules\Subscription\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'status' => $this->status,
            'starts_at' => $this->starts_at?->toIso8601String(),
            'renews_at' => $this->renews_at?->toIso8601String(),
            'ends_at' => $this->ends_at?->toIso8601String(),
            'canceled_at' => $this->canceled_at?->toIso8601String(),
            'plan' => new SubscriptionPlanResource($this->whenLoaded('plan')),
        ];
    }
}
