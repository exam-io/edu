<?php

namespace Modules\Analytics\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnalyticsEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'event_name' => $this->event_name,
            'source_module' => $this->source_module,
            'entity_type' => $this->entity_type,
            'entity_id' => $this->entity_id,
            'payload' => $this->payload,
            'occurred_at' => $this->occurred_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
