<?php

namespace Modules\Monitoring\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MetricSnapshotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'metric_key' => $this->metric_key,
            'period_key' => $this->period_key,
            'value' => (float) $this->value,
            'meta' => $this->meta ?? [],
        ];
    }
}
