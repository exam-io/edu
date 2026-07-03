<?php

namespace Modules\Identity\Application\DTOs;

readonly class LoginData
{
    public function __construct(
        public string $email,
        public string $password,
        public ?int $tenantId = null,
        public bool $remember = false,
        public ?string $ipAddress = null,
    ) {
    }
}
