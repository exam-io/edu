<?php

namespace Modules\Audit\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'actor_user_id' => $this->actor_user_id,
            'actor_type' => $this->actor_type,
            'action' => $this->action,
            'resource_type' => $this->resource_type,
            'resource_id' => $this->resource_id,
            'before_state' => $this->before_state ?? [],
            'after_state' => $this->after_state ?? [],
            'context' => $this->context ?? [],
            'occurred_at' => $this->occurred_at?->toIso8601String(),
        ];
    }
}
