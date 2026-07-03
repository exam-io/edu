<?php

namespace Modules\Identity\Application\Contracts;

use App\Models\User;
use Modules\Identity\Application\DTOs\AuthorizationCheckData;

interface AuthorizationServiceInterface
{
    public function can(User $user, AuthorizationCheckData $check): bool;
}
