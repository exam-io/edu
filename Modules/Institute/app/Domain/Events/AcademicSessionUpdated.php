<?php

namespace Modules\Institute\Domain\Events;

class AcademicSessionUpdated
{
    public function __construct(
        public readonly int $academicSessionId,
        public readonly int $instituteId,
    ) {}
}
