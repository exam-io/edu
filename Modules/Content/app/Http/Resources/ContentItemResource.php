<?php

namespace Modules\Content\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'course_id' => $this->course_id,
            'course_section_id' => $this->course_section_id,
            'media_asset_id' => $this->media_asset_id,
            'title' => $this->title,
            'content_type' => $this->content_type,
            'content_body' => $this->content_body,
            'duration_seconds' => $this->duration_seconds,
            'sort_order' => $this->sort_order,
            'is_required' => $this->is_required,
            'status' => $this->status,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
