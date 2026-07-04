<?php

namespace Modules\Assignment\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Assessment\Domain\Models\Assessment;
use Modules\Assignment\Application\Contracts\AssignmentSubmissionRepositoryInterface;
use Modules\Assignment\Application\Contracts\AssignmentSubmissionServiceInterface;
use Modules\Assignment\Application\DTOs\AssignmentSubmissionData;
use Modules\Assignment\Domain\Events\AssignmentSubmitted;
use Modules\Assignment\Domain\Models\AssignmentSubmission;
use Modules\Student\Domain\Models\Student;
use Modules\Tenant\Application\Services\TenantContextService;

class AssignmentSubmissionService implements AssignmentSubmissionServiceInterface
{
    public function __construct(
        private readonly AssignmentSubmissionRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function submit(AssignmentSubmissionData $data): AssignmentSubmission
    {
        $tenantId = $this->tenantContext->requiredTenantId();

        $assessment = Assessment::query()
            ->where('tenant_id', $tenantId)
            ->whereKey($data->assessmentId)
            ->first();

        if (! $assessment instanceof Assessment) {
            throw ValidationException::withMessages([
                'assessment_id' => ['Assessment does not exist in this tenant.'],
            ]);
        }

        if ($assessment->type !== 'Assignment') {
            throw ValidationException::withMessages([
                'assessment_id' => ['Only assessments of type Assignment can accept assignment submissions.'],
            ]);
        }

        if ($assessment->status !== 'published') {
            throw ValidationException::withMessages([
                'assessment_id' => ['Assignment must be published before submission.'],
            ]);
        }

        $student = Student::query()
            ->where('tenant_id', $tenantId)
            ->where('user_id', auth()->id())
            ->first();

        if (! $student instanceof Student) {
            throw ValidationException::withMessages([
                'student' => ['Authenticated user is not a student in this tenant.'],
            ]);
        }

        $alreadySubmitted = AssignmentSubmission::query()
            ->where('tenant_id', $tenantId)
            ->where('assessment_id', $assessment->id)
            ->where('student_id', $student->id)
            ->exists();

        if ($alreadySubmitted) {
            throw ValidationException::withMessages([
                'assignment' => ['You have already submitted this assignment.'],
            ]);
        }

        $submission = $this->repository->create([
            'tenant_id' => $tenantId,
            'assessment_id' => $assessment->id,
            'student_id' => $student->id,
            'file_path' => $data->filePath,
            'submitted_at' => now(),
            'status' => 'submitted',
        ]);

        Event::dispatch(new AssignmentSubmitted($submission->id, $assessment->id, $student->id, $tenantId));

        return $submission->load(['assessment', 'student']);
    }

    public function list(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantContext->requiredTenantId(), $perPage, $filters);
    }
}
