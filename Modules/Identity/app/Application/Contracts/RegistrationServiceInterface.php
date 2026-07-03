<?php

namespace Modules\Identity\Application\Contracts;

use App\Models\User;
use Modules\Identity\Application\DTOs\RegistrationData;

interface RegistrationServiceInterface
{
    public function register(RegistrationData $data): User;
}
