<?php

namespace Modules\Admissions\Application\DTOs;

readonly class AdmissionApplicationData
{
    public function __construct(
        public string $firstName,
        public ?string $lastName,
        public string $email,
        public ?string $phone,
        public ?int $leadId,
        public ?int $workflowId,
        public ?string $program,
        public ?string $source,
        public string $status,
        public ?string $notes,
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
            leadId: isset($payload['lead_id']) ? (int) $payload['lead_id'] : null,
            workflowId: isset($payload['workflow_id']) ? (int) $payload['workflow_id'] : null,
            program: isset($payload['program']) ? (string) $payload['program'] : null,
            source: isset($payload['source']) ? (string) $payload['source'] : null,
            status: (string) ($payload['status'] ?? 'submitted'),
            notes: isset($payload['notes']) ? (string) $payload['notes'] : null,
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
            'lead_id' => $this->leadId,
            'workflow_id' => $this->workflowId,
            'program' => $this->program,
            'source' => $this->source,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}
