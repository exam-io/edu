<?php

namespace Modules\AI\Domain\Events;

class AIGenerationCompleted
{
    public function __construct(
        public readonly int $requestId,
        public readonly int $tenantId,
        public readonly int $outputId,
        public readonly string $generationType,
    ) {}
}
