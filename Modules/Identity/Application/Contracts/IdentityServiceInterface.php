<?php

namespace Modules\Identity\Application\Contracts;

interface IdentityServiceInterface
{
    public function health(): array;
}
