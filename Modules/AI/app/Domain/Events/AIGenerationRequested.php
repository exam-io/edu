<?php

namespace Modules\AI\Domain\Events;

class AIGenerationRequested
{
    public function __construct(
        public readonly int $requestId,
        public readonly int $tenantId,
    ) {}
}
