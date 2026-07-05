<?php

namespace Modules\Operations\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Operations\Domain\Events\BackupCompleted;
use Modules\Operations\Domain\Events\QueueOperationExecuted;
use Modules\Operations\Listeners\QueueBackupNotificationListener;
use Modules\Operations\Listeners\QueueOpsAuditListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        BackupCompleted::class => [
            QueueBackupNotificationListener::class,
        ],
        QueueOperationExecuted::class => [
            QueueOpsAuditListener::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
