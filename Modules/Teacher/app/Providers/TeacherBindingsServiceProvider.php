<?php

namespace Modules\Teacher\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Teacher\Application\Contracts\TeacherProvisioningServiceInterface;
use Modules\Teacher\Application\Contracts\TeacherServiceInterface;
use Modules\Teacher\Application\Contracts\TeacherTenantRepositoryInterface;
use Modules\Teacher\Application\Services\TeacherProvisioningService;
use Modules\Teacher\Application\Services\TeacherService;
use Modules\Teacher\Infrastructure\Repositories\TeacherTenantRepository;

class TeacherBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TeacherTenantRepositoryInterface::class, TeacherTenantRepository::class);
        $this->app->bind(TeacherServiceInterface::class, TeacherService::class);
        $this->app->bind(TeacherProvisioningServiceInterface::class, TeacherProvisioningService::class);
    }
}
