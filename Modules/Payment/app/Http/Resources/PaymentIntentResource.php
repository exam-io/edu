<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentIntentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_id' => $this->invoice_id,
            'provider' => $this->provider,
            'provider_intent_id' => $this->provider_intent_id,
            'currency' => $this->currency,
            'amount' => (float) $this->amount,
            'status' => $this->status,
            'client_secret' => $this->client_secret,
            'meta' => $this->meta ?? [],
        ];
    }
}
