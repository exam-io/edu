<?php

namespace Modules\Institute\Domain\Events;

class InstituteRegistered
{
    public function __construct(
        public readonly int $instituteId,
        public readonly ?int $actorUserId,
    ) {}
}
