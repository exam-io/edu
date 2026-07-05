<?php

namespace Modules\SaaS\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsageCounterResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'metric_key' => $this->metric_key,
            'period_key' => $this->period_key,
            'counter' => (float) $this->counter,
            'meta' => $this->meta ?? [],
        ];
    }
}
