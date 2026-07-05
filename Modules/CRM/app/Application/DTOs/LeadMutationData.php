<?php

namespace Modules\CRM\Application\DTOs;

readonly class LeadMutationData
{
    public function __construct(
        public string $firstName,
        public ?string $lastName,
        public string $email,
        public ?string $phone,
        public string $source,
        public string $status,
        public ?string $interest,
        public ?string $notes,
        public ?int $assignedTo,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            firstName: (string) $payload['first_name'],
            lastName: isset($payload['last_name']) ? (string) $payload['last_name'] : null,
            email: (string) $payload['email'],
            phone: isset($payload['phone']) ? (string) $payload['phone'] : null,
            source: (string) ($payload['source'] ?? 'website'),
            status: (string) ($payload['status'] ?? 'new'),
            interest: isset($payload['interest']) ? (string) $payload['interest'] : null,
            notes: isset($payload['notes']) ? (string) $payload['notes'] : null,
            assignedTo: isset($payload['assigned_to']) ? (int) $payload['assigned_to'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'first_name' => $this->firstName,
            'last_name' => $this->lastName,
            'email' => $this->email,
            'phone' => $this->phone,
            'source' => $this->source,
            'status' => $this->status,
            'interest' => $this->interest,
            'notes' => $this->notes,
            'assigned_to' => $this->assignedTo,
        ];
    }
}
