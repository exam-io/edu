<?php

namespace Modules\Content\Listeners;

use Modules\Content\Domain\Events\ContentItemPublished;

class NotifyProgressRecalculation
{
    public function handle(ContentItemPublished $event): void
    {
        // Reserved for queue/job based progress recalculation workflow.
    }
}
