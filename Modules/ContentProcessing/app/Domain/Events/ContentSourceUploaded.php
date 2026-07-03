<?php

namespace Modules\ContentProcessing\Domain\Events;

class ContentSourceUploaded
{
    public function __construct(
        public readonly int $contentSourceId,
        public readonly int $tenantId,
    ) {}
}
