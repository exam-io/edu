<?php

namespace Modules\Assignment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Assignment\Application\Contracts\AssignmentSubmissionRepositoryInterface;
use Modules\Assignment\Application\Contracts\AssignmentSubmissionServiceInterface;
use Modules\Assignment\Application\Services\AssignmentSubmissionService;
use Modules\Assignment\Infrastructure\Repositories\AssignmentSubmissionRepository;

class AssignmentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AssignmentSubmissionRepositoryInterface::class, AssignmentSubmissionRepository::class);
        $this->app->bind(AssignmentSubmissionServiceInterface::class, AssignmentSubmissionService::class);
    }
}
