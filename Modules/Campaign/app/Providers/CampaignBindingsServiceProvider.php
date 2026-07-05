<?php

namespace Modules\Campaign\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Campaign\Application\Contracts\CampaignRepositoryInterface;
use Modules\Campaign\Application\Contracts\CampaignServiceInterface;
use Modules\Campaign\Application\Services\CampaignService;
use Modules\Campaign\Infrastructure\Repositories\CampaignRepository;

class CampaignBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CampaignRepositoryInterface::class, CampaignRepository::class);
        $this->app->bind(CampaignServiceInterface::class, CampaignService::class);
    }
}
