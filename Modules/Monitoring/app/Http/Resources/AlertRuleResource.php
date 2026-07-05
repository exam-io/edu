<?php

namespace Modules\Monitoring\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlertRuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'metric_key' => $this->metric_key,
            'operator' => $this->operator,
            'threshold' => (float) $this->threshold,
            'severity' => $this->severity,
            'is_active' => (bool) $this->is_active,
            'meta' => $this->meta ?? [],
        ];
    }
}
