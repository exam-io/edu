<?php

namespace Modules\ContentProcessing\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\ContentProcessing\Application\Contracts\ContentProcessingServiceInterface;
use Modules\ContentProcessing\Application\Contracts\ContentSourceRepositoryInterface;
use Modules\ContentProcessing\Application\Contracts\ExtractionPipelineInterface;
use Modules\ContentProcessing\Application\Services\ContentProcessingService;
use Modules\ContentProcessing\Infrastructure\Extraction\CompositeExtractionPipeline;
use Modules\ContentProcessing\Infrastructure\Repositories\ContentSourceRepository;

class ContentProcessingBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContentSourceRepositoryInterface::class, ContentSourceRepository::class);
        $this->app->bind(ExtractionPipelineInterface::class, CompositeExtractionPipeline::class);
        $this->app->bind(ContentProcessingServiceInterface::class, ContentProcessingService::class);
    }
}
