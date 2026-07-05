<?php

namespace Modules\Payment\Application\DTOs;

readonly class CapturePaymentIntentData
{
    public function __construct(
        public ?float $amount,
        public array $meta,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            amount: isset($input['amount']) ? (float) $input['amount'] : null,
            meta: is_array($input['meta'] ?? null) ? $input['meta'] : [],
        );
    }
}
