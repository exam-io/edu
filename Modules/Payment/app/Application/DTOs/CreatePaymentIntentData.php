<?php

namespace Modules\Payment\Application\DTOs;

readonly class CreatePaymentIntentData
{
    public function __construct(
        public ?int $invoiceId,
        public float $amount,
        public string $currency,
        public ?string $provider,
        public array $meta,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            invoiceId: isset($input['invoice_id']) ? (int) $input['invoice_id'] : null,
            amount: (float) $input['amount'],
            currency: (string) ($input['currency'] ?? 'USD'),
            provider: isset($input['provider']) ? (string) $input['provider'] : null,
            meta: is_array($input['meta'] ?? null) ? $input['meta'] : [],
        );
    }
}
