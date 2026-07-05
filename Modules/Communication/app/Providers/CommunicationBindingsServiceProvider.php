<?php

namespace Modules\Communication\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Communication\Application\Contracts\CommunicationRepositoryInterface;
use Modules\Communication\Application\Contracts\CommunicationServiceInterface;
use Modules\Communication\Application\Services\CommunicationService;
use Modules\Communication\Infrastructure\Repositories\CommunicationRepository;

class CommunicationBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CommunicationRepositoryInterface::class, CommunicationRepository::class);
        $this->app->bind(CommunicationServiceInterface::class, CommunicationService::class);
    }
}
