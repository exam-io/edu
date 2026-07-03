<?php

namespace Modules\QuestionBank\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\QuestionBank\Application\DTOs\QuestionSetListQueryData;
use Modules\QuestionBank\Application\DTOs\QuestionSetMutationData;
use Modules\QuestionBank\Domain\Models\QuestionSet;

interface QuestionBankServiceInterface
{
    public function list(QuestionSetListQueryData $query): LengthAwarePaginator;

    public function find(int $id): QuestionSet;

    public function create(QuestionSetMutationData $data): QuestionSet;

    public function update(int $id, QuestionSetMutationData $data): QuestionSet;

    public function delete(int $id): void;
}
