<?php

namespace Modules\QuestionBank\Listeners;

use Modules\AI\Domain\Events\AIGenerationCompleted;
use Modules\AI\Domain\Models\AIGenerationOutput;
use Modules\AI\Domain\Models\AIGenerationRequest;
use Modules\QuestionBank\Application\Contracts\QuestionRepositoryInterface;

class PersistGeneratedQuestions
{
    public function __construct(
        private readonly QuestionRepositoryInterface $repository,
    ) {}

    public function handle(AIGenerationCompleted $event): void
    {
        if ($event->generationType !== 'questions') {
            return;
        }

        $request = AIGenerationRequest::query()
            ->where('tenant_id', $event->tenantId)
            ->whereKey($event->requestId)
            ->first();

        $output = AIGenerationOutput::query()
            ->where('tenant_id', $event->tenantId)
            ->whereKey($event->outputId)
            ->first();

        if ($request === null || $output === null) {
            return;
        }

        $questions = data_get($output->structured_payload, 'questions', []);
        if (! is_array($questions) || $questions === []) {
            return;
        }

        $set = $this->repository->createSet([
            'tenant_id' => $event->tenantId,
            'ai_generation_request_id' => $request->id,
            'content_source_id' => $request->content_source_id,
            'title' => $output->title ?? 'AI Generated Questions',
            'description' => 'Auto-generated from AI output.',
            'question_type' => 'mixed',
            'difficulty' => 'medium',
            'total_questions' => count($questions),
            'status' => 'draft',
            'meta' => ['generated' => true],
        ]);

        foreach ($questions as $index => $question) {
            $this->repository->createQuestion([
                'tenant_id' => $set->tenant_id,
                'question_set_id' => $set->id,
                'stem' => (string) ($question['stem'] ?? ''),
                'question_type' => (string) ($question['question_type'] ?? 'short_answer'),
                'difficulty' => (string) ($question['difficulty'] ?? 'medium'),
                'options' => $question['options'] ?? [],
                'correct_answer' => $question['correct_answer'] ?? [],
                'explanation' => $question['explanation'] ?? null,
                'sort_order' => (int) ($question['sort_order'] ?? ($index + 1)),
                'status' => 'active',
                'meta' => ['source' => 'ai'],
            ]);
        }
    }
}
