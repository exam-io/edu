<?php

namespace Modules\AI\Infrastructure\Providers;

use Modules\AI\Application\Contracts\AIProviderClientInterface;

class NullAIProviderClient implements AIProviderClientInterface
{
    public function generate(string $generationType, string $input, array $options = []): array
    {
        $sentences = preg_split('/(?<=[.!?])\s+/', trim($input)) ?: [];
        $snippets = array_values(array_filter(array_slice($sentences, 0, 5)));

        if ($generationType === 'questions') {
            $questions = [];
            foreach ($snippets as $index => $snippet) {
                $questions[] = [
                    'stem' => 'What is the key idea in: ' . $snippet,
                    'question_type' => 'short_answer',
                    'difficulty' => 'medium',
                    'correct_answer' => ['sample' => $snippet],
                    'explanation' => 'Generated as foundational seed question.',
                    'sort_order' => $index + 1,
                ];
            }

            return [
                'title' => 'Generated Question Set',
                'body' => 'Foundation question set generated from extracted content.',
                'structured_payload' => ['questions' => $questions],
                'model_name' => 'foundation-null-provider',
            ];
        }

        if ($generationType === 'notes') {
            return [
                'title' => 'Generated Notes',
                'body' => implode("\n", array_map(static fn (string $line): string => '- ' . $line, $snippets)),
                'structured_payload' => ['bullets' => $snippets],
                'model_name' => 'foundation-null-provider',
            ];
        }

        return [
            'title' => 'Generated Summary',
            'body' => implode(' ', array_slice($snippets, 0, 3)),
            'structured_payload' => ['sentences' => array_slice($snippets, 0, 3)],
            'model_name' => 'foundation-null-provider',
        ];
    }
}
