<?php

namespace Modules\Enrollment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Enrollment\Application\Contracts\EnrollmentServiceInterface;
use Modules\Enrollment\Application\Contracts\EnrollmentTenantRepositoryInterface;
use Modules\Enrollment\Application\Contracts\TeacherAssignmentServiceInterface;
use Modules\Enrollment\Application\Services\EnrollmentService;
use Modules\Enrollment\Application\Services\TeacherAssignmentService;
use Modules\Enrollment\Infrastructure\Repositories\EnrollmentTenantRepository;

class EnrollmentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(EnrollmentTenantRepositoryInterface::class, EnrollmentTenantRepository::class);
        $this->app->bind(EnrollmentServiceInterface::class, EnrollmentService::class);
        $this->app->bind(TeacherAssignmentServiceInterface::class, TeacherAssignmentService::class);
    }
}
