<?php

namespace Modules\Institute\Domain\Events;

class AcademicSessionCreated
{
    public function __construct(
        public readonly int $academicSessionId,
        public readonly int $instituteId,
    ) {}
}
