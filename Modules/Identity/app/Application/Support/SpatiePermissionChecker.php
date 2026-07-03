<?php

namespace Modules\Identity\Application\Support;

use App\Models\User;
use Modules\Identity\Application\Interfaces\PermissionCheckerInterface;

class SpatiePermissionChecker implements PermissionCheckerInterface
{
    public function hasRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    public function hasPermission(User $user, string $permission): bool
    {
        return $user->can($permission);
    }
}
