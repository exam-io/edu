<?php

namespace Modules\Tenant\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'domain' => $this->domain,
            'custom_domain' => $this->custom_domain,
            'status' => $this->status->value,
            'plan' => $this->plan,
            'provisioned_at' => $this->provisioned_at?->toIso8601String(),
            'suspended_at' => $this->suspended_at?->toIso8601String(),
            'settings' => new TenantSettingResource($this->whenLoaded('settings')),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
