<?php

namespace Modules\Identity\Application\DTOs;

readonly class PasswordResetLinkData
{
    public function __construct(public string $email)
    {
    }
}
