<?php

namespace Modules\Assessment\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class GenerateResultAnalytics implements ShouldQueue
{
    public function handle(object $event): void
    {
        Log::info('Result analytics generation requested.', [
            'event' => $event::class,
        ]);
    }
}
