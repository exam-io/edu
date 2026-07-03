<?php

namespace Modules\Identity\Application\Services;

use App\Models\User;
use Modules\Identity\Application\Contracts\AuthorizationServiceInterface;
use Modules\Identity\Application\DTOs\AuthorizationCheckData;
use Modules\Identity\Application\Interfaces\PermissionCheckerInterface;

class AuthorizationService implements AuthorizationServiceInterface
{
    public function __construct(private readonly PermissionCheckerInterface $permissionChecker)
    {
    }

    public function can(User $user, AuthorizationCheckData $check): bool
    {
        $checks = [];

        if ($check->role !== null && $check->role !== '') {
            $checks[] = $this->permissionChecker->hasRole($user, $check->role);
        }

        if ($check->permission !== null && $check->permission !== '') {
            $checks[] = $this->permissionChecker->hasPermission($user, $check->permission);
        }

        if ($checks === []) {
            return false;
        }

        return ! in_array(false, $checks, true);
    }
}
