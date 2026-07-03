<?php

namespace Modules\Identity\Application\Contracts;

use App\Models\User;

interface CurrentUserServiceInterface
{
    public function user(): ?User;

    public function userOrFail(): User;
}
