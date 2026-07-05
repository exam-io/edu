<?php

namespace Modules\Communication\Application\DTOs;

readonly class AnnouncementData
{
    /**
     * @param list<int> $targetUserIds
     * @param list<string> $channels
     */
    public function __construct(
        public string $title,
        public string $body,
        public array $targetUserIds,
        public array $channels,
        public string $status,
        public ?string $publishAt,
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            title: (string) $payload['title'],
            body: (string) $payload['body'],
            targetUserIds: array_values(array_map('intval', $payload['target_user_ids'] ?? [])),
            channels: array_values(array_map('strval', $payload['channels'] ?? ['in_app'])),
            status: (string) ($payload['status'] ?? 'draft'),
            publishAt: isset($payload['publish_at']) ? (string) $payload['publish_at'] : null,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'body' => $this->body,
            'target_user_ids' => $this->targetUserIds,
            'channels' => $this->channels,
            'status' => $this->status,
            'publish_at' => $this->publishAt,
        ];
    }
}
