<?php

namespace Modules\QuestionBank\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\QuestionBank\Application\Contracts\QuestionRepositoryInterface;
use Modules\QuestionBank\Application\DTOs\QuestionSetListQueryData;
use Modules\QuestionBank\Domain\Models\Question;
use Modules\QuestionBank\Domain\Models\QuestionSet;

class QuestionRepository implements QuestionRepositoryInterface
{
    public function paginateSets(int $tenantId, QuestionSetListQueryData $query, array $with = []): LengthAwarePaginator
    {
        $builder = QuestionSet::query()->where('tenant_id', $tenantId)->with($with);

        if ($query->search !== null && $query->search !== '') {
            $search = $query->search;
            $builder->where(function ($q) use ($search): void {
                $q->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($query->status !== null && $query->status !== '') {
            $builder->where('status', $query->status);
        }

        if ($query->questionType !== null && $query->questionType !== '') {
            $builder->where('question_type', $query->questionType);
        }

        return $builder->latest('id')->paginate($query->perPage);
    }

    public function findSetForTenant(int $tenantId, int $id, array $with = []): ?QuestionSet
    {
        return QuestionSet::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($id)
            ->with($with)
            ->first();
    }

    public function createSet(array $attributes): QuestionSet
    {
        return QuestionSet::query()->create($attributes);
    }

    public function updateSet(QuestionSet $set, array $attributes): QuestionSet
    {
        $set->update($attributes);

        return $set->refresh();
    }

    public function createQuestion(array $attributes): Question
    {
        return Question::query()->create($attributes);
    }

    public function deleteSet(QuestionSet $set): void
    {
        $set->delete();
    }
}
