<?php

namespace Modules\Operations\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Operations\Application\Contracts\BackupServiceInterface;
use Modules\Operations\Application\Contracts\QueueOpsServiceInterface;
use Modules\Operations\Application\Services\BackupService;
use Modules\Operations\Application\Services\QueueOpsService;

class OperationsBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BackupServiceInterface::class, BackupService::class);
        $this->app->bind(QueueOpsServiceInterface::class, QueueOpsService::class);
    }
}
