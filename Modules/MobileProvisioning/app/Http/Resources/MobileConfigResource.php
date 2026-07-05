<?php

namespace Modules\MobileProvisioning\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileConfigResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'version' => (int) $this->version,
            'config' => $this->config ?? [],
            'published_at' => $this->published_at?->toIso8601String(),
            'published_by_user_id' => $this->published_by_user_id,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
