<?php

namespace Modules\Identity\Events;

readonly class EmailVerified
{
    public function __construct(
        public int $userId,
        public ?int $tenantId,
    ) {
    }
}
