<?php

namespace Modules\Shared\Application\Actions;

use Illuminate\Contracts\Events\Dispatcher;

class DispatchDomainEventAction
{
    public function __construct(private readonly Dispatcher $dispatcher)
    {
    }

    public function execute(object $event): void
    {
        $this->dispatcher->dispatch($event);
    }
}
