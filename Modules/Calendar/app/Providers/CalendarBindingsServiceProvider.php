<?php

namespace Modules\Calendar\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Calendar\Application\Contracts\CalendarEventRepositoryInterface;
use Modules\Calendar\Application\Contracts\CalendarEventServiceInterface;
use Modules\Calendar\Application\Services\CalendarEventService;
use Modules\Calendar\Infrastructure\Repositories\CalendarEventRepository;

class CalendarBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CalendarEventRepositoryInterface::class, CalendarEventRepository::class);
        $this->app->bind(CalendarEventServiceInterface::class, CalendarEventService::class);
    }
}
