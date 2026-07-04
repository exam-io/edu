<?php

namespace Modules\QuestionBank\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Modules\QuestionBank\Application\Contracts\QuestionBankServiceInterface;
use Modules\QuestionBank\Application\Contracts\QuestionRepositoryInterface;
use Modules\QuestionBank\Application\DTOs\QuestionSetListQueryData;
use Modules\QuestionBank\Application\DTOs\QuestionSetMutationData;
use Modules\QuestionBank\Domain\Events\QuestionSetGenerated;
use Modules\QuestionBank\Domain\Models\QuestionSet;
use Modules\QuestionBank\Domain\Services\QuestionValidator;
use Modules\Tenant\Application\Services\TenantContextService;

class QuestionBankService implements QuestionBankServiceInterface
{
    public function __construct(
        private readonly QuestionRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
        private readonly QuestionValidator $questionValidator,
    ) {}

    public function list(QuestionSetListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginateSets($this->tenantId(), $query, ['questions']);
    }

    public function find(int $id): QuestionSet
    {
        $set = $this->repository->findSetForTenant($this->tenantId(), $id, ['questions']);
        if (! $set instanceof QuestionSet) {
            throw (new ModelNotFoundException())->setModel(QuestionSet::class, [$id]);
        }

        return $set;
    }

    public function create(QuestionSetMutationData $data): QuestionSet
    {
        $attributes = $data->attributes;
        $questions = $attributes['questions'] ?? [];

        if (is_array($questions) && $questions !== []) {
            $questions = $this->questionValidator->validateOrFail($questions, $this->tenantId());
        }

        $set = $this->repository->createSet([
            'tenant_id' => $this->tenantId(),
            'ai_generation_request_id' => $attributes['ai_generation_request_id'] ?? null,
            'content_source_id' => $attributes['content_source_id'] ?? null,
            'title' => (string) $attributes['title'],
            'description' => $attributes['description'] ?? null,
            'question_type' => (string) ($attributes['question_type'] ?? 'mixed'),
            'difficulty' => (string) ($attributes['difficulty'] ?? 'medium'),
            'total_questions' => is_array($questions) ? count($questions) : 0,
            'status' => (string) ($attributes['status'] ?? 'draft'),
            'meta' => $attributes['meta'] ?? [],
        ]);

        if (is_array($questions)) {
            foreach ($questions as $index => $question) {
                $this->repository->createQuestion([
                    'tenant_id' => $set->tenant_id,
                    'question_set_id' => $set->id,
                    'stem' => (string) ($question['stem'] ?? ''),
                    'question_type' => (string) ($question['question_type'] ?? 'short_answer'),
                    'difficulty' => (string) ($question['difficulty'] ?? $set->difficulty),
                    'options' => $question['options'] ?? [],
                    'correct_answer' => $question['correct_answer'] ?? [],
                    'explanation' => $question['explanation'] ?? null,
                    'sort_order' => (int) ($question['sort_order'] ?? ($index + 1)),
                    'status' => (string) ($question['status'] ?? 'active'),
                    'meta' => $question['meta'] ?? [],
                ]);
            }
        }

        Event::dispatch(new QuestionSetGenerated($set->id, $set->tenant_id));

        return $set->refresh()->load('questions');
    }

    public function update(int $id, QuestionSetMutationData $data): QuestionSet
    {
        $set = $this->find($id);
        $attributes = $data->attributes;

        return $this->repository->updateSet($set, [
            'title' => $attributes['title'] ?? $set->title,
            'description' => $attributes['description'] ?? $set->description,
            'question_type' => $attributes['question_type'] ?? $set->question_type,
            'difficulty' => $attributes['difficulty'] ?? $set->difficulty,
            'status' => $attributes['status'] ?? $set->status,
            'meta' => $attributes['meta'] ?? $set->meta,
        ])->load('questions');
    }

    public function delete(int $id): void
    {
        $set = $this->find($id);
        $this->repository->deleteSet($set);
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
