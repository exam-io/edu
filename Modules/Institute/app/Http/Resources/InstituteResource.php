<?php

namespace Modules\Institute\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InstituteResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'name' => $this->name,
            'slug' => $this->slug,
            'code' => $this->code,
            'status' => $this->status?->value ?? $this->status,
            'email' => $this->email,
            'phone' => $this->phone,
            'website' => $this->website,
            'description' => $this->description,
            'address' => $this->address,
            'branding' => $this->branding,
            'configuration' => $this->configuration,
            'onboarding_step' => $this->onboarding_step,
            'onboarded_at' => $this->onboarded_at?->toIso8601String(),
            'current_academic_session' => new AcademicSessionResource($this->whenLoaded('currentAcademicSession')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
