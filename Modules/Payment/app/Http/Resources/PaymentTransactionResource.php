<?php

namespace Modules\Payment\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'payment_intent_id' => $this->payment_intent_id,
            'provider' => $this->provider,
            'provider_transaction_id' => $this->provider_transaction_id,
            'status' => $this->status,
            'currency' => $this->currency,
            'amount' => (float) $this->amount,
            'processed_at' => $this->processed_at?->toIso8601String(),
            'meta' => $this->meta ?? [],
        ];
    }
}
