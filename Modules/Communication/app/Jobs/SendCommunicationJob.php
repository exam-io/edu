<?php

namespace Modules\Communication\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Communication\Application\Contracts\CommunicationRepositoryInterface;

class SendCommunicationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param list<string> $channels
     */
    public function __construct(
        private readonly int $tenantId,
        private readonly string $sourceType,
        private readonly int $sourceId,
        private readonly int $userId,
        private readonly string $subject,
        private readonly string $content,
        private readonly array $channels,
    ) {}

    public function handle(CommunicationRepositoryInterface $repository): void
    {
        foreach ($this->channels as $channel) {
            $repository->createHistory([
                'tenant_id' => $this->tenantId,
                'source_type' => $this->sourceType,
                'source_id' => $this->sourceId,
                'user_id' => $this->userId,
                'channel' => $channel,
                'subject' => $this->subject,
                'content' => $this->content,
                'status' => 'sent',
                'provider_response' => ['message' => 'queued_delivery', 'channel' => $channel],
                'sent_at' => now(),
            ]);
        }
    }
}
