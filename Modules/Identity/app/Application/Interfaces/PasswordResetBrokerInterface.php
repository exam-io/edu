<?php

namespace Modules\Identity\Application\Interfaces;

interface PasswordResetBrokerInterface
{
    public function sendResetLink(string $email): string;

    public function reset(string $email, string $token, string $password): string;
}
