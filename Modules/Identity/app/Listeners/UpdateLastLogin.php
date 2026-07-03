<?php

namespace Modules\Identity\Listeners;

use App\Models\User;
use Modules\Identity\Events\UserLoggedIn;

class UpdateLastLogin
{
    public function handle(UserLoggedIn $event): void
    {
        User::query()
            ->whereKey($event->userId)
            ->update([
                'last_login_at' => now(),
                'last_login_ip' => $event->ipAddress,
            ]);
    }
}
