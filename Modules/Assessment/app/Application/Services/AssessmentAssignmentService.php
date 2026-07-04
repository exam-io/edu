<?php

namespace Modules\Assessment\Application\Services;

use Illuminate\Validation\ValidationException;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Program;
use Modules\Academic\Domain\Models\Section;
use Modules\Assessment\Application\Contracts\AssessmentAssignmentServiceInterface;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Institute\Domain\Models\AcademicSession;

class AssessmentAssignmentService implements AssessmentAssignmentServiceInterface
{
    public function syncAssignments(Assessment $assessment, array $assignments): Assessment
    {
        $assessment->assignments()->delete();

        foreach ($assignments as $assignment) {
            $academicSessionId = $this->nullableInt($assignment['academic_session_id'] ?? null);
            $programId = $this->nullableInt($assignment['program_id'] ?? null);
            $classId = $this->nullableInt($assignment['class_id'] ?? null);
            $sectionId = $this->nullableInt($assignment['section_id'] ?? null);
            $batchId = $this->nullableInt($assignment['batch_id'] ?? null);

            $this->validateAcademicEntities(
                tenantId: (int) $assessment->tenant_id,
                academicSessionId: $academicSessionId,
                programId: $programId,
                classId: $classId,
                sectionId: $sectionId,
                batchId: $batchId,
            );

            $assessment->assignments()->create([
                'tenant_id' => $assessment->tenant_id,
                'academic_session_id' => $academicSessionId,
                'program_id' => $programId,
                'class_id' => $classId,
                'section_id' => $sectionId,
                'batch_id' => $batchId,
            ]);
        }

        return $assessment->refresh()->load('assignments');
    }

    private function validateAcademicEntities(
        int $tenantId,
        ?int $academicSessionId,
        ?int $programId,
        ?int $classId,
        ?int $sectionId,
        ?int $batchId,
    ): void {
        if ($academicSessionId !== null) {
            $session = AcademicSession::query()->find($academicSessionId);
            if (! $session || $session->institute?->tenant_id !== $tenantId) {
                throw ValidationException::withMessages([
                    'assignments' => ['Invalid academic_session_id for tenant.'],
                ]);
            }
        }

        if ($programId !== null && ! Program::query()->where('tenant_id', $tenantId)->whereKey($programId)->exists()) {
            throw ValidationException::withMessages(['assignments' => ['Invalid program_id for tenant.']]);
        }

        if ($classId !== null && ! AcademicClass::query()->where('tenant_id', $tenantId)->whereKey($classId)->exists()) {
            throw ValidationException::withMessages(['assignments' => ['Invalid class_id for tenant.']]);
        }

        if ($sectionId !== null && ! Section::query()->where('tenant_id', $tenantId)->whereKey($sectionId)->exists()) {
            throw ValidationException::withMessages(['assignments' => ['Invalid section_id for tenant.']]);
        }

        if ($batchId !== null && ! Batch::query()->where('tenant_id', $tenantId)->whereKey($batchId)->exists()) {
            throw ValidationException::withMessages(['assignments' => ['Invalid batch_id for tenant.']]);
        }
    }

    private function nullableInt(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return (int) $value;
    }
}
