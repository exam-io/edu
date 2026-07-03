<?php

namespace Modules\Identity\Application\Services;

use Modules\Identity\Application\Contracts\IdentityServiceInterface;

class IdentityService implements IdentityServiceInterface
{
    public function health(): array
    {
        return ['module' => 'identity', 'status' => 'ready'];
    }
}
