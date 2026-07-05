<?php

namespace Modules\Subscription\Application\DTOs;

readonly class PlanMutationData
{
    public function __construct(
        public ?int $id,
        public string $code,
        public string $name,
        public ?string $description,
        public string $billingInterval,
        public float $priceAmount,
        public string $currency,
        public array $quota,
        public bool $isActive,
        public array $meta,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            id: isset($input['id']) ? (int) $input['id'] : null,
            code: (string) $input['code'],
            name: (string) $input['name'],
            description: isset($input['description']) ? (string) $input['description'] : null,
            billingInterval: (string) ($input['billing_interval'] ?? 'monthly'),
            priceAmount: (float) $input['price_amount'],
            currency: (string) ($input['currency'] ?? 'USD'),
            quota: is_array($input['quota'] ?? null) ? $input['quota'] : [],
            isActive: (bool) ($input['is_active'] ?? true),
            meta: is_array($input['meta'] ?? null) ? $input['meta'] : [],
        );
    }
}
