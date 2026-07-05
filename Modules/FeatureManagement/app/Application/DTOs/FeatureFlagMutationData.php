<?php

namespace Modules\FeatureManagement\Application\DTOs;

readonly class FeatureFlagMutationData
{
    public function __construct(public array $flags) {}

    public static function fromArray(array $payload): self
    {
        return new self(flags: isset($payload['flags']) && is_array($payload['flags']) ? $payload['flags'] : []);
    }
}
