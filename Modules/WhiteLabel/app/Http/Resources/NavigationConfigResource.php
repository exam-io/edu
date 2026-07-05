<?php

namespace Modules\WhiteLabel\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NavigationConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'version' => (int) $this->version,
            'items' => $this->items ?? [],
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
