<?php

namespace Modules\Institute\Domain\Events;

class AcademicSessionDeleted
{
    public function __construct(
        public readonly int $academicSessionId,
        public readonly int $instituteId,
    ) {}
}
