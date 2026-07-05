<?php

namespace Modules\WhiteLabel\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DomainResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'host' => $this->host,
            'is_primary' => (bool) $this->is_primary,
            'status' => $this->status,
            'verification_token' => $this->verification_token,
            'verified_at' => $this->verified_at?->toIso8601String(),
        ];
    }
}
