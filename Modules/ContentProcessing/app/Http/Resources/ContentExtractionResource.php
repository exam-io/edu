<?php

namespace Modules\ContentProcessing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentExtractionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content_source_id' => $this->content_source_id,
            'status' => $this->status,
            'word_count' => $this->word_count,
            'error_message' => $this->error_message,
            'meta' => $this->meta,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
