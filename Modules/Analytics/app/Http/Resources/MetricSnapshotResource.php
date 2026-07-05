<?php

namespace Modules\Analytics\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetricSnapshotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'metric_key' => $this->metric_key,
            'dimension_key' => $this->dimension_key,
            'dimension_value' => $this->dimension_value,
            'metric_value' => (float) $this->metric_value,
            'period_start' => $this->period_start?->toIso8601String(),
            'period_end' => $this->period_end?->toIso8601String(),
            'generated_at' => $this->generated_at?->toIso8601String(),
        ];
    }
}
