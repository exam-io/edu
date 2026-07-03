<?php

namespace Modules\Media\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Media\Application\Contracts\MediaRepositoryInterface;
use Modules\Media\Application\Contracts\MediaServiceInterface;
use Modules\Media\Application\Services\MediaService;
use Modules\Media\Infrastructure\Repositories\MediaRepository;

class MediaBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(MediaRepositoryInterface::class, MediaRepository::class);
        $this->app->bind(MediaServiceInterface::class, MediaService::class);
    }
}
