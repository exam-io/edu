<?php

namespace Modules\Communication\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommunicationHistoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'source_type' => $this->source_type,
            'source_id' => $this->source_id,
            'user_id' => $this->user_id,
            'channel' => $this->channel,
            'subject' => $this->subject,
            'content' => $this->content,
            'status' => $this->status,
            'provider_response' => $this->provider_response,
            'sent_at' => $this->sent_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
