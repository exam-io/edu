<?php

namespace Modules\Audit\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Audit\Domain\Events\AuditRecordCreated;
use Modules\Audit\Listeners\QueueAuditExportListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        AuditRecordCreated::class => [
            QueueAuditExportListener::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
