<?php

namespace Modules\Identity\Application\DTOs;

readonly class RegistrationData
{
    public function __construct(
        public int $tenantId,
        public string $name,
        public string $email,
        public string $password,
        public string $status = 'active',
        public ?string $role = null,
        public string $language = 'en',
        public string $theme = 'light',
        public string $timezone = 'UTC',
    ) {
    }
}
