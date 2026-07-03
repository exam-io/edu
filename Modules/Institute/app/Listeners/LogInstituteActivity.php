<?php

namespace Modules\Institute\Listeners;

use Illuminate\Support\Facades\Log;

class LogInstituteActivity
{
    public function handle(object $event): void
    {
        Log::info('Institute activity event dispatched.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
