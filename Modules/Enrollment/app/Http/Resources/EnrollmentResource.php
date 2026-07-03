<?php

namespace Modules\Enrollment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EnrollmentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'student_id' => $this->student_id,
            'academic_session_id' => $this->academic_session_id,
            'class_id' => $this->class_id,
            'section_id' => $this->section_id,
            'batch_id' => $this->batch_id,
            'enrollment_date' => $this->enrollment_date?->toDateString(),
            'status' => $this->status,
            'student' => $this->whenLoaded('student'),
            'academic_session' => $this->whenLoaded('academicSession'),
            'class' => $this->whenLoaded('class'),
            'section' => $this->whenLoaded('section'),
            'batch' => $this->whenLoaded('batch'),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
