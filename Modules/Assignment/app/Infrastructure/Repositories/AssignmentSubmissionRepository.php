<?php

namespace Modules\Assignment\Infrastructure\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Assignment\Application\Contracts\AssignmentSubmissionRepositoryInterface;
use Modules\Assignment\Domain\Models\AssignmentSubmission;

class AssignmentSubmissionRepository implements AssignmentSubmissionRepositoryInterface
{
    public function create(array $attributes): AssignmentSubmission
    {
        return AssignmentSubmission::query()->create($attributes);
    }

    public function paginate(int $tenantId, int $perPage, array $filters = []): LengthAwarePaginator
    {
        $builder = AssignmentSubmission::query()
            ->where('tenant_id', $tenantId)
            ->with(['assessment', 'student'])
            ->latest('id');

        if (! empty($filters['status'])) {
            $builder->where('status', (string) $filters['status']);
        }

        if (! empty($filters['assessment_id'])) {
            $builder->where('assessment_id', (int) $filters['assessment_id']);
        }

        if (! empty($filters['student_id'])) {
            $builder->where('student_id', (int) $filters['student_id']);
        }

        if (! empty($filters['q'])) {
            $q = (string) $filters['q'];
            $builder->where(function ($query) use ($q): void {
                $query->where('file_path', 'like', '%' . $q . '%')
                    ->orWhere('feedback', 'like', '%' . $q . '%');
            });
        }

        return $builder->paginate($perPage);
    }
}
