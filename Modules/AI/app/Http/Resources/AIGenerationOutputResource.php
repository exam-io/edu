<?php

namespace Modules\AI\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AIGenerationOutputResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'output_type' => $this->output_type,
            'title' => $this->title,
            'body' => $this->body,
            'structured_payload' => $this->structured_payload,
            'model_name' => $this->model_name,
            'token_usage_input' => $this->token_usage_input,
            'token_usage_output' => $this->token_usage_output,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
