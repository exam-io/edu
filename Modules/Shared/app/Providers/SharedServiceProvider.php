<?php

namespace Modules\Shared\Providers;

use Nwidart\Modules\Support\ModuleServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Modules\Shared\Application\Contracts\SharedLookupServiceInterface;
use Modules\Shared\Application\Services\SharedLookupService;

class SharedServiceProvider extends ModuleServiceProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->bind(SharedLookupServiceInterface::class, SharedLookupService::class);
    }

    /**
     * The name of the module.
     */
    protected string $name = 'Shared';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'shared';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     * 
     * @param $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }
}
