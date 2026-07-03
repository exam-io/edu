<?php

namespace Modules\Student\Application\Contracts;

use Modules\Student\Domain\Models\Student;

interface StudentRelationshipServiceInterface
{
    public function syncParents(Student $student, array $parentIds, mixed $primaryParentId): void;
}
