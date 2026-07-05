<?php

namespace Modules\Operations\Domain\Events;

readonly class QueueOperationExecuted
{
    public function __construct(
        public int $tenantId,
        public string $operation,
        public array $meta = [],
    ) {}
}
