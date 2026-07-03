<?php

namespace Modules\AI\Listeners;

use Modules\AI\Domain\Events\AIGenerationRequested;
use Modules\AI\Jobs\RunAIGenerationJob;

class QueueAIGeneration
{
    public function handle(AIGenerationRequested $event): void
    {
        RunAIGenerationJob::dispatch($event->requestId, $event->tenantId);
    }
}
