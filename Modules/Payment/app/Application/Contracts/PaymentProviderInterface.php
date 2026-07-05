<?php

namespace Modules\Payment\Application\Contracts;

interface PaymentProviderInterface
{
    public function providerKey(): string;

    public function createIntent(array $payload): array;

    public function captureIntent(string $providerIntentId, array $payload = []): array;
}
