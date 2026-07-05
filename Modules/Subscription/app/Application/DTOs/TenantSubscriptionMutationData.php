<?php

namespace Modules\Subscription\Application\DTOs;

readonly class TenantSubscriptionMutationData
{
    public function __construct(
        public int $planId,
        public string $status,
        public ?string $startsAt,
        public ?string $renewsAt,
        public ?string $endsAt,
        public array $meta,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            planId: (int) $input['plan_id'],
            status: (string) ($input['status'] ?? 'active'),
            startsAt: isset($input['starts_at']) ? (string) $input['starts_at'] : null,
            renewsAt: isset($input['renews_at']) ? (string) $input['renews_at'] : null,
            endsAt: isset($input['ends_at']) ? (string) $input['ends_at'] : null,
            meta: is_array($input['meta'] ?? null) ? $input['meta'] : [],
        );
    }
}
