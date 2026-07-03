<?php

namespace Modules\Course\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'created_by' => $this->created_by,
            'title' => $this->title,
            'code' => $this->code,
            'description' => $this->description,
            'level' => $this->level,
            'duration_minutes' => $this->duration_minutes,
            'price' => (float) $this->price,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
