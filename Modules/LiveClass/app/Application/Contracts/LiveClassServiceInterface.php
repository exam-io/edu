<?php

namespace Modules\LiveClass\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\LiveClass\Application\DTOs\LiveClassMutationData;
use Modules\LiveClass\Application\DTOs\LiveClassQueryData;
use Modules\LiveClass\Domain\Models\LiveClassAttendance;
use Modules\LiveClass\Domain\Models\LiveClassSession;

interface LiveClassServiceInterface
{
    public function list(LiveClassQueryData $query): LengthAwarePaginator;

    public function find(int $id): LiveClassSession;

    public function create(LiveClassMutationData $data): LiveClassSession;

    public function update(int $id, LiveClassMutationData $data): LiveClassSession;

    public function delete(int $id): void;

    public function start(int $id): LiveClassSession;

    public function join(int $id): LiveClassSession;

    public function end(int $id): LiveClassSession;

    /**
     * @return array<int, LiveClassAttendance>
     */
    public function attendance(int $id): array;
}
