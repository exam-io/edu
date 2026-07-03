<?php

namespace Modules\Course\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Course\Application\Contracts\CourseRepositoryInterface;
use Modules\Course\Application\Contracts\CourseServiceInterface;
use Modules\Course\Application\Services\CourseService;
use Modules\Course\Infrastructure\Repositories\CourseRepository;

class CourseBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CourseRepositoryInterface::class, CourseRepository::class);
        $this->app->bind(CourseServiceInterface::class, CourseService::class);
    }
}
