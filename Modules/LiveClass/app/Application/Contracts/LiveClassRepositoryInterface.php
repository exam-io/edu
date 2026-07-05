<?php

namespace Modules\LiveClass\Application\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\LiveClass\Application\DTOs\LiveClassQueryData;
use Modules\LiveClass\Domain\Models\LiveClassAttendance;
use Modules\LiveClass\Domain\Models\LiveClassSession;

interface LiveClassRepositoryInterface
{
    public function paginate(int $tenantId, LiveClassQueryData $query): LengthAwarePaginator;

    public function findForTenant(int $tenantId, int $id, array $with = []): ?LiveClassSession;

    public function create(array $attributes): LiveClassSession;

    public function update(LiveClassSession $session, array $attributes): LiveClassSession;

    public function delete(LiveClassSession $session): void;

    public function upsertAttendance(int $tenantId, int $liveClassId, int $studentId, array $attributes = []): LiveClassAttendance;

    /**
     * @return array<int, LiveClassAttendance>
     */
    public function listAttendance(int $tenantId, int $liveClassId): array;
}
