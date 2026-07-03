<?php

namespace Modules\Media\Listeners;

use Illuminate\Support\Facades\Log;

class LogMediaActivity
{
    public function handle(object $event): void
    {
        Log::info('Media domain event handled.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
