<?php

namespace Modules\Insights\Application\DTOs;

readonly class InsightQueryData
{
    public function __construct(
        public ?string $q,
        public ?string $severity,
        public int $perPage,
    ) {}

    public static function fromArray(array $input): self
    {
        return new self(
            q: isset($input['q']) ? (string) $input['q'] : null,
            severity: isset($input['severity']) ? (string) $input['severity'] : null,
            perPage: (int) ($input['per_page'] ?? 15),
        );
    }
}
