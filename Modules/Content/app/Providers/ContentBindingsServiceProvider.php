<?php

namespace Modules\Content\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Content\Application\Contracts\ContentItemServiceInterface;
use Modules\Content\Application\Contracts\ContentRepositoryInterface;
use Modules\Content\Application\Contracts\CourseSectionServiceInterface;
use Modules\Content\Application\Services\ContentItemService;
use Modules\Content\Application\Services\CourseSectionService;
use Modules\Content\Infrastructure\Repositories\ContentRepository;

class ContentBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->bind(CourseSectionServiceInterface::class, CourseSectionService::class);
        $this->app->bind(ContentItemServiceInterface::class, ContentItemService::class);
    }
}
