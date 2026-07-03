<?php

namespace Modules\Institute\Domain\Events;

class InstituteBrandingUpdated
{
    public function __construct(
        public readonly int $instituteId,
    ) {}
}
