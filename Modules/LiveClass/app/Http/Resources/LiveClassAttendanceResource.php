<?php

namespace Modules\LiveClass\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveClassAttendanceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'live_class_session_id' => $this->live_class_session_id,
            'student_id' => $this->student_id,
            'joined_at' => $this->joined_at?->toIso8601String(),
            'left_at' => $this->left_at?->toIso8601String(),
            'duration_seconds' => $this->duration_seconds,
            'attendance_status' => $this->attendance_status,
            'source' => $this->source,
            'meta' => $this->meta,
        ];
    }
}
