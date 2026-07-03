<?php

namespace Modules\ContentProcessing\Domain\Events;

class ContentExtracted
{
    public function __construct(
        public readonly int $contentSourceId,
        public readonly int $tenantId,
        public readonly int $extractionId,
    ) {}
}
