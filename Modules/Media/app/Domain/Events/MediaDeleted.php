<?php

namespace Modules\Media\Domain\Events;

class MediaDeleted
{
    public function __construct(
        public readonly int $mediaAssetId,
        public readonly int $tenantId,
    ) {}
}
