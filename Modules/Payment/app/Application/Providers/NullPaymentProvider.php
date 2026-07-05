<?php

namespace Modules\Payment\Application\Providers;

use Illuminate\Support\Str;
use Modules\Payment\Application\Contracts\PaymentProviderInterface;

class NullPaymentProvider implements PaymentProviderInterface
{
    public function providerKey(): string
    {
        return 'null';
    }

    public function createIntent(array $payload): array
    {
        return [
            'provider_intent_id' => 'null_pi_' . Str::lower(Str::random(12)),
            'client_secret' => 'null_secret_' . Str::lower(Str::random(18)),
            'status' => 'requires_capture',
            'meta' => ['simulated' => true, 'payload' => $payload],
        ];
    }

    public function captureIntent(string $providerIntentId, array $payload = []): array
    {
        return [
            'provider_transaction_id' => 'null_txn_' . Str::lower(Str::random(12)),
            'status' => 'succeeded',
            'meta' => ['simulated' => true, 'intent' => $providerIntentId, 'payload' => $payload],
        ];
    }
}
