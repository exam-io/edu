<?php

namespace Modules\Billing\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Billing\Domain\Events\InvoiceGenerationRequested;
use Modules\Billing\Listeners\QueueInvoiceGeneration;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        InvoiceGenerationRequested::class => [
            QueueInvoiceGeneration::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
