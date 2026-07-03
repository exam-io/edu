<?php

namespace Modules\Content\Domain\Events;

class ContentItemPublished
{
    public function __construct(
        public readonly int $contentItemId,
        public readonly int $courseId,
        public readonly int $tenantId,
    ) {}
}
