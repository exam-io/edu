<?php

namespace Modules\ContentProcessing\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContentSourceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $latestExtraction = $this->extractions->sortByDesc('id')->first();

        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'uploaded_by' => $this->uploaded_by,
            'title' => $this->title,
            'source_type' => $this->source_type,
            'source_ref' => $this->source_ref,
            'mime_type' => $this->mime_type,
            'status' => $this->status,
            'meta' => $this->meta,
            'processed_at' => $this->processed_at?->toIso8601String(),
            'latest_extraction' => $latestExtraction ? new ContentExtractionResource($latestExtraction) : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
