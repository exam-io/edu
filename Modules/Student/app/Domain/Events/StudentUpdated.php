<?php

namespace Modules\Student\Domain\Events;

class StudentUpdated
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $tenantId,
    ) {}
}
