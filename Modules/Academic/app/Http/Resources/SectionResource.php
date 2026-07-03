<?php

namespace Modules\Academic\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'class_id' => $this->class_id,
            'name' => $this->name,
            'code' => $this->code,
            'capacity' => $this->capacity,
            'status' => $this->status,
            'class' => new ClassResource($this->whenLoaded('class')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
