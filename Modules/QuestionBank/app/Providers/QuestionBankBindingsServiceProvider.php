<?php

namespace Modules\QuestionBank\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\QuestionBank\Application\Contracts\QuestionBankServiceInterface;
use Modules\QuestionBank\Application\Contracts\QuestionRepositoryInterface;
use Modules\QuestionBank\Application\Services\QuestionBankService;
use Modules\QuestionBank\Infrastructure\Repositories\QuestionRepository;

class QuestionBankBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(QuestionRepositoryInterface::class, QuestionRepository::class);
        $this->app->bind(QuestionBankServiceInterface::class, QuestionBankService::class);
    }
}
