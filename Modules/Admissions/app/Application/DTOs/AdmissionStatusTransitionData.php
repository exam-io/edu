<?php

namespace Modules\Admissions\Application\DTOs;

readonly class AdmissionStatusTransitionData
{
    public function __construct(
        public string $toStatus,
        public ?string $note,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            toStatus: (string) $payload['status'],
            note: isset($payload['note']) ? (string) $payload['note'] : null,
        );
    }
}
