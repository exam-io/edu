<?php

namespace Modules\Institute\Domain\Events;

class InstituteProvisioned
{
    public function __construct(
        public readonly int $instituteId,
        public readonly ?int $defaultAcademicSessionId,
    ) {}
}
