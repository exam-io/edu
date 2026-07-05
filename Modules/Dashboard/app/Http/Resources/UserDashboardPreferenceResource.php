<?php

namespace Modules\Dashboard\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDashboardPreferenceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'user_id' => $this->user_id,
            'dashboard_definition_id' => $this->dashboard_definition_id,
            'preferences' => $this->preferences,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
