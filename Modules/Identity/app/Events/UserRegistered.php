<?php

namespace Modules\Identity\Events;

readonly class UserRegistered
{
    public function __construct(
        public int $userId,
        public ?int $tenantId,
        public string $email,
        public string $name,
        public string $language = 'en',
        public string $theme = 'light',
        public string $timezone = 'UTC',
    ) {
    }
}
