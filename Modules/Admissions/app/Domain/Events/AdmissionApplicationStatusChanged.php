<?php

namespace Modules\Admissions\Domain\Events;

readonly class AdmissionApplicationStatusChanged
{
    public function __construct(
        public int $applicationId,
        public int $tenantId,
        public string $fromStatus,
        public string $toStatus,
    ) {}
}
