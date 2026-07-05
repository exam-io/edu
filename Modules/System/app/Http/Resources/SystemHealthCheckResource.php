<?php

namespace Modules\System\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SystemHealthCheckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'check_name' => $this->check_name,
            'status' => $this->status,
            'checked_at' => $this->checked_at?->toIso8601String(),
            'meta' => $this->meta ?? [],
        ];
    }
}
