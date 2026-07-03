<?php

namespace Modules\ContentProcessing\Listeners;

use Modules\ContentProcessing\Domain\Events\ContentSourceUploaded;
use Modules\ContentProcessing\Jobs\ExtractContentJob;

class QueueContentExtraction
{
    public function handle(ContentSourceUploaded $event): void
    {
        ExtractContentJob::dispatch($event->contentSourceId);
    }
}
