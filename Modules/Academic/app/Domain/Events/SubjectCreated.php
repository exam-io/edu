<?php

namespace Modules\Academic\Domain\Events;

class SubjectCreated
{
    public function __construct(
        public readonly int $subjectId,
        public readonly int $tenantId,
    ) {}
}
