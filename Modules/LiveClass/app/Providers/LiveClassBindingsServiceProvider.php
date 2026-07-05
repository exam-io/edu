<?php

namespace Modules\LiveClass\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\LiveClass\Application\Contracts\AttendanceServiceInterface;
use Modules\LiveClass\Application\Contracts\JitsiProviderInterface;
use Modules\LiveClass\Application\Contracts\LiveClassRepositoryInterface;
use Modules\LiveClass\Application\Contracts\LiveClassServiceInterface;
use Modules\LiveClass\Application\Services\AttendanceService;
use Modules\LiveClass\Application\Services\LiveClassService;
use Modules\LiveClass\Infrastructure\Providers\JitsiProvider;
use Modules\LiveClass\Infrastructure\Repositories\LiveClassRepository;

class LiveClassBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LiveClassRepositoryInterface::class, LiveClassRepository::class);
        $this->app->bind(JitsiProviderInterface::class, JitsiProvider::class);
        $this->app->bind(LiveClassServiceInterface::class, LiveClassService::class);
        $this->app->bind(AttendanceServiceInterface::class, AttendanceService::class);
    }
}
