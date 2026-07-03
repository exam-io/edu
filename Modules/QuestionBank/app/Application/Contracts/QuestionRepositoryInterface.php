<?php

namespace Modules\QuestionBank\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\QuestionBank\Application\DTOs\QuestionSetListQueryData;
use Modules\QuestionBank\Domain\Models\Question;
use Modules\QuestionBank\Domain\Models\QuestionSet;

interface QuestionRepositoryInterface
{
    public function paginateSets(int $tenantId, QuestionSetListQueryData $query, array $with = []): LengthAwarePaginator;

    public function findSetForTenant(int $tenantId, int $id, array $with = []): ?QuestionSet;

    public function createSet(array $attributes): QuestionSet;

    public function updateSet(QuestionSet $set, array $attributes): QuestionSet;

    public function createQuestion(array $attributes): Question;

    public function deleteSet(QuestionSet $set): void;
}
