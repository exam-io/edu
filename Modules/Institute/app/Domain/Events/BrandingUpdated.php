<?php

namespace Modules\Institute\Domain\Events;

class BrandingUpdated
{
    public function __construct(
        public readonly int $instituteId,
        public readonly ?int $actorUserId = null,
    ) {}
}
