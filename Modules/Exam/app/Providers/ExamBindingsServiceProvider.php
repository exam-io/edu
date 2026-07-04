<?php

namespace Modules\Exam\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Exam\Application\Contracts\ExamFacadeServiceInterface;
use Modules\Exam\Application\Services\ExamFacadeService;

class ExamBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ExamFacadeServiceInterface::class, ExamFacadeService::class);
    }
}
