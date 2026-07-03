<?php

namespace Modules\AI\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Modules\AI\Application\Contracts\AIProviderClientInterface;
use Modules\AI\Application\Contracts\AIRequestRepositoryInterface;
use Modules\AI\Domain\Events\AIGenerationCompleted;
use Modules\AI\Domain\Models\AIGenerationRequest;
use Modules\ContentProcessing\Domain\Models\ContentExtraction;

class RunAIGenerationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public readonly int $requestId,
        public readonly int $tenantId,
    ) {
        $this->onQueue('ai-generation');
    }

    public function handle(AIRequestRepositoryInterface $repository, AIProviderClientInterface $client): void
    {
        $request = $repository->findForTenant($this->tenantId, $this->requestId);

        if (! $request instanceof AIGenerationRequest) {
            return;
        }

        $repository->updateRequest($request, ['status' => 'processing']);

        $input = (string) ($request->prompt_text ?? '');

        if ($request->content_source_id !== null) {
            $latestExtraction = ContentExtraction::query()
                ->where('tenant_id', $request->tenant_id)
                ->where('content_source_id', $request->content_source_id)
                ->where('status', 'success')
                ->latest('id')
                ->first();

            if ($latestExtraction instanceof ContentExtraction && $latestExtraction->extracted_text !== null) {
                $input = trim($input . "\n" . $latestExtraction->extracted_text);
            }
        }

        $result = $client->generate($request->generation_type, $input, is_array($request->options) ? $request->options : []);

        $output = $repository->createOutput([
            'tenant_id' => $request->tenant_id,
            'ai_generation_request_id' => $request->id,
            'output_type' => $request->generation_type,
            'title' => $result['title'] ?? null,
            'body' => $result['body'] ?? null,
            'structured_payload' => $result['structured_payload'] ?? [],
            'model_name' => $result['model_name'] ?? null,
            'token_usage_input' => isset($result['token_usage_input']) ? (int) $result['token_usage_input'] : null,
            'token_usage_output' => isset($result['token_usage_output']) ? (int) $result['token_usage_output'] : null,
        ]);

        $repository->updateRequest($request, [
            'status' => 'completed',
            'processed_at' => now(),
            'error_message' => null,
        ]);

        Event::dispatch(new AIGenerationCompleted($request->id, $request->tenant_id, $output->id, $request->generation_type));
    }

    public function failed(\Throwable $exception): void
    {
        Log::error('AI generation job failed.', [
            'request_id' => $this->requestId,
            'tenant_id' => $this->tenantId,
            'error' => $exception->getMessage(),
        ]);
    }
}
