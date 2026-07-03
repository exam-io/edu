<?php

namespace Modules\Identity\Events;

readonly class PasswordResetRequested
{
    public function __construct(
        public string $email,
        public ?int $tenantId,
    ) {
    }
}
