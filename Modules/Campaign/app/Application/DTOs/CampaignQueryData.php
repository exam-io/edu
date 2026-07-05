<?php

namespace Modules\Campaign\Application\DTOs;

readonly class CampaignQueryData
{
    public function __construct(
        public int $perPage = 15,
        public ?string $status = null,
        public ?string $campaignType = null,
        public ?string $search = null,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            perPage: max(1, min(100, (int) ($payload['per_page'] ?? 15))),
            status: isset($payload['status']) ? (string) $payload['status'] : null,
            campaignType: isset($payload['campaign_type']) ? (string) $payload['campaign_type'] : null,
            search: isset($payload['search']) ? trim((string) $payload['search']) : null,
        );
    }
}
