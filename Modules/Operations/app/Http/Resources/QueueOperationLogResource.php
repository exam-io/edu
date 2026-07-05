<?php

namespace Modules\Operations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QueueOperationLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'operation' => $this->operation,
            'status' => $this->status,
            'meta' => $this->meta ?? [],
            'executed_at' => $this->executed_at?->toIso8601String(),
        ];
    }
}
