<?php

namespace Modules\Academic\Domain\Events;

class SectionCreated
{
    public function __construct(
        public readonly int $sectionId,
        public readonly int $tenantId,
    ) {}
}
