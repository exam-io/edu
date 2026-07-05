<?php

namespace Modules\WhiteLabel\Application\DTOs;

readonly class NavigationMutationData
{
    public function __construct(
        public array $items,
    ) {}

    public static function fromArray(array $payload): self
    {
        return new self(
            items: isset($payload['items']) && is_array($payload['items']) ? $payload['items'] : [],
        );
    }
}
