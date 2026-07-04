<?php

namespace Modules\Assignment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignmentSubmissionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'assessment_id' => $this->assessment_id,
            'student_id' => $this->student_id,
            'file_path' => $this->file_path,
            'submitted_at' => $this->submitted_at?->toIso8601String(),
            'score' => $this->score !== null ? (float) $this->score : null,
            'feedback' => $this->feedback,
            'status' => $this->status,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
