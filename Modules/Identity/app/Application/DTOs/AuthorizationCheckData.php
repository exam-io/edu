<?php

namespace Modules\Identity\Application\DTOs;

readonly class AuthorizationCheckData
{
    public function __construct(
        public ?string $role = null,
        public ?string $permission = null,
    ) {
    }
}
