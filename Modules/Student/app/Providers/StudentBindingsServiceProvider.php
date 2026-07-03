<?php

namespace Modules\Student\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Student\Application\Contracts\StudentRelationshipServiceInterface;
use Modules\Student\Application\Contracts\StudentProvisioningServiceInterface;
use Modules\Student\Application\Contracts\StudentServiceInterface;
use Modules\Student\Application\Contracts\StudentTenantRepositoryInterface;
use Modules\Student\Application\Services\StudentRelationshipService;
use Modules\Student\Application\Services\StudentProvisioningService;
use Modules\Student\Application\Services\StudentService;
use Modules\Student\Infrastructure\Repositories\StudentTenantRepository;

class StudentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(StudentTenantRepositoryInterface::class, StudentTenantRepository::class);
        $this->app->bind(StudentRelationshipServiceInterface::class, StudentRelationshipService::class);
        $this->app->bind(StudentServiceInterface::class, StudentService::class);
        $this->app->bind(StudentProvisioningServiceInterface::class, StudentProvisioningService::class);
    }
}
