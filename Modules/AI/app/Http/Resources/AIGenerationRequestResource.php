<?php

namespace Modules\AI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIGenerationRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'requested_by' => $this->requested_by,
            'content_source_id' => $this->content_source_id,
            'generation_type' => $this->generation_type,
            'status' => $this->status,
            'prompt_text' => $this->prompt_text,
            'options' => $this->options,
            'error_message' => $this->error_message,
            'processed_at' => $this->processed_at?->toIso8601String(),
            'outputs' => AIGenerationOutputResource::collection($this->whenLoaded('outputs')),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
