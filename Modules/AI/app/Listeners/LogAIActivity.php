<?php

namespace Modules\AI\Listeners;

use Illuminate\Support\Facades\Log;

class LogAIActivity
{
    public function handle(object $event): void
    {
        Log::info('AI domain event handled.', [
            'event' => $event::class,
            'payload' => get_object_vars($event),
        ]);
    }
}
