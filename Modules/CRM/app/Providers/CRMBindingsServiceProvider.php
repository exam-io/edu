<?php

namespace Modules\CRM\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\CRM\Application\Contracts\LeadRepositoryInterface;
use Modules\CRM\Application\Contracts\LeadServiceInterface;
use Modules\CRM\Application\Services\LeadService;
use Modules\CRM\Infrastructure\Repositories\LeadRepository;

class CRMBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(LeadRepositoryInterface::class, LeadRepository::class);
        $this->app->bind(LeadServiceInterface::class, LeadService::class);
    }
}
