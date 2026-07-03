<?php

namespace Modules\Institute\Domain\Events;

class InstituteUpdated
{
    public function __construct(
        public readonly int $instituteId,
        public readonly ?int $actorUserId = null,
    ) {}
}
