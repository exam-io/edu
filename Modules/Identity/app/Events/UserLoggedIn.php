<?php

namespace Modules\Identity\Events;

readonly class UserLoggedIn
{
    public function __construct(
        public int $userId,
        public ?int $tenantId,
        public ?string $ipAddress,
    ) {
    }
}
