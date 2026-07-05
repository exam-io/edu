<?php

namespace Modules\System\Application\DTOs;

readonly class HealthQueryData
{
    public function __construct(public bool $refresh) {}

    public static function fromArray(array $input): self
    {
        return new self(refresh: (bool) ($input['refresh'] ?? false));
    }
}
