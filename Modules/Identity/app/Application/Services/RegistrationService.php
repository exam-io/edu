<?php

namespace Modules\Identity\Application\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Identity\Application\Contracts\RegistrationServiceInterface;
use Modules\Identity\Application\DTOs\RegistrationData;

class RegistrationService implements RegistrationServiceInterface
{
    public function register(RegistrationData $data): User
    {
        return DB::transaction(function () use ($data): User {
            $user = User::query()->create([
                'tenant_id' => $data->tenantId,
                'name' => $data->name,
                'email' => $data->email,
                'password' => $data->password,
                'status' => $data->status,
            ]);

            if ($data->role !== null && $data->role !== '') {
                $user->assignRole($data->role);
            }

            return $user->refresh();
        });
    }
}
