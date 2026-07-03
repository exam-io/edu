<?php

namespace Modules\QuestionBank\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionSetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'ai_generation_request_id' => $this->ai_generation_request_id,
            'content_source_id' => $this->content_source_id,
            'title' => $this->title,
            'description' => $this->description,
            'question_type' => $this->question_type,
            'difficulty' => $this->difficulty,
            'total_questions' => $this->total_questions,
            'status' => $this->status,
            'meta' => $this->meta,
            'questions' => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
