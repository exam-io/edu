<?php

namespace Modules\Identity\Application\Interfaces;

use App\Models\User;

interface PermissionCheckerInterface
{
    public function hasRole(User $user, string $role): bool;

    public function hasPermission(User $user, string $permission): bool;
}
