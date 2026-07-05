<?php

namespace Modules\Campaign\Application\DTOs;

readonly class CampaignMutationData
{
    /**
     * @param list<int> $recipientUserIds
     * @param list<string> $channels
     */
    public function __construct(
        public string $name,
        public string $campaignType,
        public string $subject,
        public string $message,
        public array $channels,
        public array $recipientUserIds,
        public string $status,
        public ?string $scheduledAt,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            name: (string) $payload['name'],
            campaignType: (string) ($payload['campaign_type'] ?? 'broadcast'),
            subject: (string) $payload['subject'],
            message: (string) $payload['message'],
            channels: array_values(array_map('strval', $payload['channels'] ?? ['in_app'])),
            recipientUserIds: array_values(array_map('intval', $payload['recipient_user_ids'] ?? [])),
            status: (string) ($payload['status'] ?? 'draft'),
            scheduledAt: isset($payload['scheduled_at']) ? (string) $payload['scheduled_at'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'campaign_type' => $this->campaignType,
            'subject' => $this->subject,
            'message' => $this->message,
            'channels' => $this->channels,
            'status' => $this->status,
            'scheduled_at' => $this->scheduledAt,
        ];
    }
}
