<?php

namespace Modules\Academic\Domain\Events;

class ClassCreated
{
    public function __construct(
        public readonly int $classId,
        public readonly int $tenantId,
    ) {}
}
