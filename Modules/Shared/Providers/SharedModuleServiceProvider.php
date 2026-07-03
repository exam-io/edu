<?php

namespace Modules\Shared\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class SharedModuleServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        Event::listen('*', static function (string $eventName): void {
            Log::debug('Module event observed', ['event' => $eventName]);
        });
    }
}
