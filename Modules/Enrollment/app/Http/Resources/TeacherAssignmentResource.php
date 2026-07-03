<?php

namespace Modules\Enrollment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAssignmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'teacher_id' => $this->teacher_id,
            'academic_session_id' => $this->academic_session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'batch_id' => $this->batch_id,
            'subject_id' => $this->subject_id,
            'start_date' => $this->start_date?->toDateString(),
            'end_date' => $this->end_date?->toDateString(),
            'status' => $this->status,
            'teacher' => $this->whenLoaded('teacher'),
            'academic_session' => $this->whenLoaded('academicSession'),
            'class' => $this->whenLoaded('class'),
            'section' => $this->whenLoaded('section'),
            'batch' => $this->whenLoaded('batch'),
            'subject' => $this->whenLoaded('subject'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
