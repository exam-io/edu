<?php

namespace Modules\Audit\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Audit\Application\Contracts\AuditServiceInterface;
use Modules\Audit\Application\Services\AuditService;

class AuditBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AuditServiceInterface::class, AuditService::class);
    }
}
