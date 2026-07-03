<?php

namespace Modules\Content\Listeners;

use Illuminate\Support\Facades\Log;

class LogContentActivity
{
    public function handle(object $event): void
    {
        Log::info('Content domain event handled.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
