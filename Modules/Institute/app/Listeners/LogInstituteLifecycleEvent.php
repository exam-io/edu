<?php

namespace Modules\Institute\Listeners;

use Illuminate\Support\Facades\Log;

class LogInstituteLifecycleEvent
{
    public function handle(object $event): void
    {
        Log::info('Institute module event dispatched.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
