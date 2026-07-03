<?php

namespace Modules\Identity\Application\Contracts;

use App\Models\User;
use Modules\Identity\Application\DTOs\LoginData;

interface AuthenticationServiceInterface
{
    public function authenticate(LoginData $data): ?User;

    public function logout(): void;
}
