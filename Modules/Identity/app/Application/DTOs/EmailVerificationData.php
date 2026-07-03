<?php

namespace Modules\Identity\Application\DTOs;

readonly class EmailVerificationData
{
    public function __construct(
        public int $userId,
        public string $emailHash,
        public ?int $tenantId = null,
    ) {
    }
}
