<?php

namespace Modules\ContentProcessing\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Modules\ContentProcessing\Application\Services\ContentProcessingService;

class ExtractContentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly int $contentSourceId,
    ) {
        $this->onQueue('content-processing');
    }

    public function handle(ContentProcessingService $service): void
    {
        $service->processExtraction($this->contentSourceId);
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('Content extraction job failed.', [
            'content_source_id' => $this->contentSourceId,
            'error' => $exception->getMessage(),
        ]);
    }
}
