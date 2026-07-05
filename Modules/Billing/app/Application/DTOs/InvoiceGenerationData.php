<?php

namespace Modules\Billing\Application\DTOs;

readonly class InvoiceGenerationData
{
    public function __construct(
        public string $currency,
        public string $periodStart,
        public string $periodEnd,
        public array $lineItems,
        public ?string $dueAt,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            currency: (string) ($input['currency'] ?? config('billing.currency', 'USD')),
            periodStart: (string) $input['period_start'],
            periodEnd: (string) $input['period_end'],
            lineItems: is_array($input['line_items'] ?? null) ? $input['line_items'] : [],
            dueAt: isset($input['due_at']) ? (string) $input['due_at'] : null,
        );
    }
}
