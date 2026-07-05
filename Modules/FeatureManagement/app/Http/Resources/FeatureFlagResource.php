<?php

namespace Modules\FeatureManagement\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureFlagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'feature_key' => $this->feature_key,
            'enabled' => (bool) $this->enabled,
            'source' => $this->source,
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
