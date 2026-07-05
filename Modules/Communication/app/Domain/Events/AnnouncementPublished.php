<?php

namespace Modules\Communication\Domain\Events;

readonly class AnnouncementPublished
{
    public function __construct(
        public int $announcementId,
        public int $tenantId,
    ) {}
}
