<?php

namespace Modules\LiveClass\Application\DTOs;

use Carbon\CarbonImmutable;

readonly class LiveClassMutationData
{
    /**
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public string $title,
        public ?string $description,
        public int $hostUserId,
        public ?int $classId,
        public ?int $sectionId,
        public ?int $subjectId,
        public CarbonImmutable $scheduledStartAt,
        public CarbonImmutable $scheduledEndAt,
        public ?int $maxParticipants,
        public string $attendancePolicy,
        public array $meta = [],
    ) {}

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            title: (string) ($payload['title'] ?? ''),
            description: isset($payload['description']) ? (string) $payload['description'] : null,
            hostUserId: (int) $payload['host_user_id'],
            classId: isset($payload['class_id']) ? (int) $payload['class_id'] : null,
            sectionId: isset($payload['section_id']) ? (int) $payload['section_id'] : null,
            subjectId: isset($payload['subject_id']) ? (int) $payload['subject_id'] : null,
            scheduledStartAt: CarbonImmutable::parse($payload['scheduled_start_at']),
            scheduledEndAt: CarbonImmutable::parse($payload['scheduled_end_at']),
            maxParticipants: isset($payload['max_participants']) ? (int) $payload['max_participants'] : null,
            attendancePolicy: (string) ($payload['attendance_policy'] ?? 'open'),
            meta: isset($payload['meta']) && is_array($payload['meta']) ? $payload['meta'] : [],
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'host_user_id' => $this->hostUserId,
            'class_id' => $this->classId,
            'section_id' => $this->sectionId,
            'subject_id' => $this->subjectId,
            'scheduled_start_at' => $this->scheduledStartAt,
            'scheduled_end_at' => $this->scheduledEndAt,
            'max_participants' => $this->maxParticipants,
            'attendance_policy' => $this->attendancePolicy,
            'meta' => $this->meta,
        ];
    }
}
