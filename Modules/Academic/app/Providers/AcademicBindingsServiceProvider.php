<?php

namespace Modules\Academic\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Academic\Application\Contracts\AcademicStructureServiceInterface;
use Modules\Academic\Application\Contracts\BatchServiceInterface;
use Modules\Academic\Application\Contracts\ClassServiceInterface;
use Modules\Academic\Application\Contracts\DepartmentServiceInterface;
use Modules\Academic\Application\Contracts\ProgramServiceInterface;
use Modules\Academic\Application\Contracts\SectionServiceInterface;
use Modules\Academic\Application\Contracts\SubjectServiceInterface;
use Modules\Academic\Application\Contracts\TenantScopedRepositoryInterface;
use Modules\Academic\Application\Services\AcademicStructureService;
use Modules\Academic\Application\Services\BatchService;
use Modules\Academic\Application\Services\ClassService;
use Modules\Academic\Application\Services\DepartmentService;
use Modules\Academic\Application\Services\ProgramService;
use Modules\Academic\Application\Services\SectionService;
use Modules\Academic\Application\Services\SubjectService;
use Modules\Academic\Infrastructure\Repositories\TenantScopedRepository;

class AcademicBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TenantScopedRepositoryInterface::class, TenantScopedRepository::class);

        $this->app->bind(DepartmentServiceInterface::class, DepartmentService::class);
        $this->app->bind(ProgramServiceInterface::class, ProgramService::class);
        $this->app->bind(ClassServiceInterface::class, ClassService::class);
        $this->app->bind(SectionServiceInterface::class, SectionService::class);
        $this->app->bind(BatchServiceInterface::class, BatchService::class);
        $this->app->bind(SubjectServiceInterface::class, SubjectService::class);
        $this->app->bind(AcademicStructureServiceInterface::class, AcademicStructureService::class);
    }
}
