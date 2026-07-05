<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentWebhookLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'provider' => $this->provider,
            'event_key' => $this->event_key,
            'status' => $this->status,
            'processed_at' => $this->processed_at?->toIso8601String(),
            'error_message' => $this->error_message,
        ];
    }
}
