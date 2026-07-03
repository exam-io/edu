<?php

namespace Modules\LMS\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LearningProgressResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'course_id' => $this->course_id,
            'student_id' => $this->student_id,
            'content_item_id' => $this->content_item_id,
            'progress_percent' => $this->progress_percent,
            'completed_items' => $this->completed_items,
            'total_items' => $this->total_items,
            'last_activity_at' => $this->last_activity_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
