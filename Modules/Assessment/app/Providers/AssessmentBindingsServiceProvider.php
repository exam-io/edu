<?php

namespace Modules\Assessment\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Assessment\Application\Contracts\AssessmentAssignmentServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentAttemptServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentBuilderServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentEvaluationServiceInterface;
use Modules\Assessment\Application\Contracts\AssessmentRepositoryInterface;
use Modules\Assessment\Application\Contracts\AssessmentServiceInterface;
use Modules\Assessment\Application\Contracts\RankingServiceInterface;
use Modules\Assessment\Application\Contracts\ResultServiceInterface;
use Modules\Assessment\Application\Services\AssessmentAssignmentService;
use Modules\Assessment\Application\Services\AssessmentAttemptService;
use Modules\Assessment\Application\Services\AssessmentBuilderService;
use Modules\Assessment\Application\Services\AssessmentEvaluationService;
use Modules\Assessment\Application\Services\AssessmentService;
use Modules\Assessment\Application\Services\RankingService;
use Modules\Assessment\Application\Services\ResultService;
use Modules\Assessment\Infrastructure\Repositories\AssessmentRepository;

class AssessmentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AssessmentRepositoryInterface::class, AssessmentRepository::class);
        $this->app->bind(AssessmentServiceInterface::class, AssessmentService::class);
        $this->app->bind(AssessmentBuilderServiceInterface::class, AssessmentBuilderService::class);
        $this->app->bind(AssessmentAssignmentServiceInterface::class, AssessmentAssignmentService::class);
        $this->app->bind(AssessmentAttemptServiceInterface::class, AssessmentAttemptService::class);
        $this->app->bind(AssessmentEvaluationServiceInterface::class, AssessmentEvaluationService::class);
        $this->app->bind(ResultServiceInterface::class, ResultService::class);
        $this->app->bind(RankingServiceInterface::class, RankingService::class);
    }
}
