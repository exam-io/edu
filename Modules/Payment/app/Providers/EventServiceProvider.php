<?php

namespace Modules\Payment\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Payment\Domain\Events\PaymentWebhookReceived;
use Modules\Payment\Listeners\QueuePaymentWebhookProcessing;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentWebhookReceived::class => [
            QueuePaymentWebhookProcessing::class,
        ],
    ];

    protected static $shouldDiscoverEvents = false;
}
