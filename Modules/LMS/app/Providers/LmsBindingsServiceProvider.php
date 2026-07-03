<?php

namespace Modules\LMS\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\LMS\Application\Contracts\CourseEnrollmentServiceInterface;
use Modules\LMS\Application\Contracts\LearningProgressServiceInterface;
use Modules\LMS\Application\Contracts\LmsRepositoryInterface;
use Modules\LMS\Application\Services\CourseEnrollmentService;
use Modules\LMS\Application\Services\LearningProgressService;
use Modules\LMS\Infrastructure\Repositories\LmsRepository;

class LmsBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LmsRepositoryInterface::class, LmsRepository::class);
        $this->app->bind(CourseEnrollmentServiceInterface::class, CourseEnrollmentService::class);
        $this->app->bind(LearningProgressServiceInterface::class, LearningProgressService::class);
    }
}
