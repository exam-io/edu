<?php

namespace Modules\Identity\Listeners;

use App\Models\User;
use Modules\Identity\Events\UserRegistered;

class CreateDefaultSettings
{
    public function handle(UserRegistered $event): void
    {
        $user = User::query()->find($event->userId);

        if (! $user instanceof User) {
            return;
        }

        $user->settings()->firstOrCreate([], [
            'language' => $event->language,
            'theme' => $event->theme,
            'timezone' => $event->timezone,
        ]);
    }
}
