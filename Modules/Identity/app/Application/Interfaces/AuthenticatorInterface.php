<?php

namespace Modules\Identity\Application\Interfaces;

use Illuminate\Contracts\Auth\Authenticatable;

interface AuthenticatorInterface
{
    /** @param array<string, mixed> $credentials */
    public function attempt(array $credentials, bool $remember = false): bool;

    public function logout(): void;

    public function user(): ?Authenticatable;
}
