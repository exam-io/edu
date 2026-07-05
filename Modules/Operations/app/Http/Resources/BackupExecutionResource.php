<?php

namespace Modules\Operations\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BackupExecutionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'storage_disk' => $this->storage_disk,
            'storage_path' => $this->storage_path,
            'started_at' => $this->started_at?->toIso8601String(),
            'completed_at' => $this->completed_at?->toIso8601String(),
            'meta' => $this->meta ?? [],
        ];
    }
}
