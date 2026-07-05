<?php

namespace Modules\MobileProvisioning\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MobileProvisioningRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'platform' => $this->platform,
            'status' => $this->status,
            'requested_by_user_id' => $this->requested_by_user_id,
            'notes' => $this->notes,
            'requested_at' => $this->requested_at?->toIso8601String(),
        ];
    }
}
