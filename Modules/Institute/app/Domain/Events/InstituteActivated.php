<?php

namespace Modules\Institute\Domain\Events;

class InstituteActivated
{
    public function __construct(
        public readonly int $instituteId,
        public readonly ?int $actorUserId = null,
    ) {}
}
