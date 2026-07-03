<?php

namespace Modules\Academic\Application\Contracts;

interface AcademicStructureServiceInterface
{
    public function tenantId(): int;

    public function assertTenantOwned(string $modelClass, int $id): object;
}
