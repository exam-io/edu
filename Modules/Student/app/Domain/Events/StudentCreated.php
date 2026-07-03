<?php

namespace Modules\Student\Domain\Events;

class StudentCreated
{
    public function __construct(
        public readonly int $studentId,
        public readonly int $tenantId,
        public readonly bool $provisionLoginAccount = false,
    ) {}
}
