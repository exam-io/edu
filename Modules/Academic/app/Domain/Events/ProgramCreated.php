<?php

namespace Modules\Academic\Domain\Events;

class ProgramCreated
{
    public function __construct(
        public readonly int $programId,
        public readonly int $tenantId,
    ) {}
}
