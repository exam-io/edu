<?php

namespace Modules\SaaS\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsageSnapshotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'snapshot_date' => $this->snapshot_date?->toDateString(),
            'metrics' => $this->metrics ?? [],
            'mrr' => (float) $this->mrr,
            'arr' => (float) $this->arr,
            'active_subscribers' => (int) $this->active_subscribers,
            'meta' => $this->meta ?? [],
        ];
    }
}
