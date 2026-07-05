<?php

namespace Modules\Payment\Application\DTOs;

readonly class WebhookPayloadData
{
    public function __construct(
        public string $provider,
        public string $eventKey,
        public array $payload,
    ) {}

    public static function fromArray(string $provider, array $input): self
    {
        return new self(
            provider: $provider,
            eventKey: (string) ($input['event'] ?? 'unknown'),
            payload: $input,
        );
    }
}
