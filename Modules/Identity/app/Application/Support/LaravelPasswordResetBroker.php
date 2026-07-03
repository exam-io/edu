<?php

namespace Modules\Identity\Application\Support;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Modules\Identity\Application\Interfaces\PasswordResetBrokerInterface;

class LaravelPasswordResetBroker implements PasswordResetBrokerInterface
{
    public function sendResetLink(string $email): string
    {
        return Password::sendResetLink(['email' => $email]);
    }

    public function reset(string $email, string $token, string $password): string
    {
        return Password::reset(
            [
                'email' => $email,
                'token' => $token,
                'password' => $password,
            ],
            function (User $user, string $newPassword): void {
                $user->forceFill(['password' => Hash::make($newPassword)])->save();
            },
        );
    }
}
