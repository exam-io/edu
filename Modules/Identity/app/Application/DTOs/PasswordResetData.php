<?php

namespace Modules\Identity\Application\DTOs;

readonly class PasswordResetData
{
    public function __construct(
        public string $email,
        public string $token,
        public string $password,
    ) {
    }
}
