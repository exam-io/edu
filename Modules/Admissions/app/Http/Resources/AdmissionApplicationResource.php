<?php

namespace Modules\Admissions\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdmissionApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'lead_id' => $this->lead_id,
            'workflow_id' => $this->workflow_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim(($this->first_name ?? '') . ' ' . ($this->last_name ?? '')),
            'email' => $this->email,
            'phone' => $this->phone,
            'program' => $this->program,
            'source' => $this->source,
            'status' => $this->status,
            'notes' => $this->notes,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'reviewed_at' => $this->reviewed_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
