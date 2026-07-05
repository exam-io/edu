<?php

namespace Modules\LiveClass\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LiveClassSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'title' => $this->title,
            'description' => $this->description,
            'host_user_id' => $this->host_user_id,
            'host' => $this->whenLoaded('host', fn (): array => [
                'id' => $this->host->id,
                'name' => $this->host->name,
            ]),
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'subject_id' => $this->subject_id,
            'provider' => $this->provider,
            'provider_meeting_id' => $this->provider_meeting_id,
            'room_name' => $this->room_name,
            'meeting_url' => $this->meeting_url,
            'scheduled_start_at' => $this->scheduled_start_at?->toIso8601String(),
            'scheduled_end_at' => $this->scheduled_end_at?->toIso8601String(),
            'actual_start_at' => $this->actual_start_at?->toIso8601String(),
            'actual_end_at' => $this->actual_end_at?->toIso8601String(),
            'attendance_policy' => $this->attendance_policy,
            'max_participants' => $this->max_participants,
            'status' => $this->status,
            'meta' => $this->meta,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
            'attendances' => $this->whenLoaded('attendances', fn () => LiveClassAttendanceResource::collection($this->attendances)),
        ];
    }
}
