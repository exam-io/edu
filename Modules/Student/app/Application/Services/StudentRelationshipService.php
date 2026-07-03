<?php

namespace Modules\Student\Application\Services;

use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Parent\Domain\Models\ParentProfile;
use Modules\Student\Application\Contracts\StudentRelationshipServiceInterface;
use Modules\Student\Domain\Events\ParentLinkedToStudent;
use Modules\Student\Domain\Models\Student;
use Modules\Student\Domain\Models\StudentParent;
use Modules\Tenant\Application\Services\TenantContextService;

class StudentRelationshipService implements StudentRelationshipServiceInterface
{
    public function __construct(
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function syncParents(Student $student, array $parentIds, mixed $primaryParentId): void
    {
        $tenantId = $this->tenantContext->requiredTenantId();
        $sanitizedParentIds = collect($parentIds)
            ->map(static fn ($id) => (int) $id)
            ->filter(static fn (int $id) => $id > 0)
            ->unique()
            ->values();

        if ($sanitizedParentIds->isEmpty()) {
            return;
        }

        $count = ParentProfile::query()
            ->where('tenant_id', $tenantId)
            ->whereIn('id', $sanitizedParentIds->all())
            ->count();

        if ($count !== $sanitizedParentIds->count()) {
            throw ValidationException::withMessages([
                'parent_ids' => ['All parents must belong to tenant.'],
            ]);
        }

        StudentParent::query()->where('tenant_id', $tenantId)->where('student_id', $student->id)->delete();

        $primaryParentId = $primaryParentId !== null ? (int) $primaryParentId : null;

        foreach ($sanitizedParentIds as $parentId) {
            StudentParent::query()->create([
                'tenant_id' => $tenantId,
                'student_id' => $student->id,
                'parent_id' => $parentId,
                'is_primary' => $primaryParentId !== null && $primaryParentId === $parentId,
            ]);

            Event::dispatch(new ParentLinkedToStudent($student->id, $parentId, $tenantId));
        }
    }
}
