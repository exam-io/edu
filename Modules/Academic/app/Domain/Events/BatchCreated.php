<?php

namespace Modules\Academic\Domain\Events;

class BatchCreated
{
    public function __construct(
        public readonly int $batchId,
        public readonly int $tenantId,
    ) {}
}
