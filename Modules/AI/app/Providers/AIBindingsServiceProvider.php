<?php

namespace Modules\AI\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\AI\Application\Contracts\AIGenerationServiceInterface;
use Modules\AI\Application\Contracts\AIProviderClientInterface;
use Modules\AI\Application\Contracts\AIRequestRepositoryInterface;
use Modules\AI\Application\Services\AIGenerationService;
use Modules\AI\Infrastructure\Providers\NullAIProviderClient;
use Modules\AI\Infrastructure\Providers\OpenAIProviderClient;
use Modules\AI\Infrastructure\Repositories\AIRequestRepository;

class AIBindingsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AIRequestRepositoryInterface::class, AIRequestRepository::class);

        $this->app->bind(AIProviderClientInterface::class, function () {
            $provider = (string) config('ai.provider', 'null');
            $hasOpenAiKey = (string) config('ai.openai.api_key', '') !== '';

            if ($provider === 'openai' && $hasOpenAiKey) {
                return $this->app->make(OpenAIProviderClient::class);
            }

            return $this->app->make(NullAIProviderClient::class);
        });

        $this->app->bind(AIGenerationServiceInterface::class, AIGenerationService::class);
    }
}
