<?php

namespace Modules\AI\Infrastructure\Providers;

use Illuminate\Support\Facades\Http;
use Modules\AI\Application\Contracts\AIProviderClientInterface;

class OpenAIProviderClient implements AIProviderClientInterface
{
    public function generate(string $generationType, string $input, array $options = []): array
    {
        $apiKey = (string) config('ai.openai.api_key', '');
        $baseUrl = rtrim((string) config('ai.openai.base_url', 'https://api.openai.com/v1'), '/');
        $model = (string) ($options['model'] ?? config('ai.openai.model', 'gpt-4o-mini'));
        $timeout = (int) config('ai.timeout_seconds', 30);

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->withToken($apiKey)
            ->post($baseUrl . '/chat/completions', [
                'model' => $model,
                'temperature' => $options['temperature'] ?? 0.3,
                'response_format' => ['type' => 'json_object'],
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => $this->systemPrompt($generationType),
                    ],
                    [
                        'role' => 'user',
                        'content' => $input,
                    ],
                ],
            ]);

        $response->throw();

        $payload = $response->json();
        $content = (string) data_get($payload, 'choices.0.message.content', '{}');
        $decoded = json_decode($content, true);
        $decoded = is_array($decoded) ? $decoded : [];

        return [
            'title' => (string) ($decoded['title'] ?? ucfirst($generationType) . ' Output'),
            'body' => (string) ($decoded['body'] ?? ''),
            'structured_payload' => is_array($decoded['structured_payload'] ?? null)
                ? $decoded['structured_payload']
                : $decoded,
            'model_name' => (string) data_get($payload, 'model', $model),
            'token_usage_input' => (int) data_get($payload, 'usage.prompt_tokens', 0),
            'token_usage_output' => (int) data_get($payload, 'usage.completion_tokens', 0),
        ];
    }

    private function systemPrompt(string $generationType): string
    {
        if ($generationType === 'questions') {
            return 'Generate pedagogically sound questions. Respond ONLY as JSON with keys: title, body, structured_payload. structured_payload must include questions array.';
        }

        if ($generationType === 'notes') {
            return 'Generate concise study notes. Respond ONLY as JSON with keys: title, body, structured_payload.';
        }

        return 'Generate a concise summary. Respond ONLY as JSON with keys: title, body, structured_payload.';
    }
}
