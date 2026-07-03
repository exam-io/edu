<?php

namespace Modules\Course\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Course\Application\Contracts\CourseRepositoryInterface;
use Modules\Course\Application\Contracts\CourseServiceInterface;
use Modules\Course\Application\DTOs\CourseListQueryData;
use Modules\Course\Application\DTOs\CourseMutationData;
use Modules\Course\Domain\Events\CourseArchived;
use Modules\Course\Domain\Events\CourseCreated;
use Modules\Course\Domain\Events\CourseUpdated;
use Modules\Course\Domain\Models\Course;
use Modules\Tenant\Application\Services\TenantContextService;

class CourseService implements CourseServiceInterface
{
    public function __construct(
        private readonly CourseRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(CourseListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate($this->tenantId(), $query, ['creator']);
    }

    public function find(int $id): Course
    {
        $course = $this->repository->findForTenant($this->tenantId(), $id, ['creator']);
        if (! $course instanceof Course) {
            throw (new ModelNotFoundException())->setModel(Course::class, [$id]);
        }

        return $course;
    }

    public function create(CourseMutationData $data): Course
    {
        $attributes = $data->attributes;
        $this->ensureUniqueCode((string) $attributes['code']);

        $attributes['tenant_id'] = $this->tenantId();
        $attributes['created_by'] = auth()->id();

        /** @var Course $course */
        $course = $this->repository->create($attributes);

        Event::dispatch(new CourseCreated($course->id, $course->tenant_id));

        return $course->refresh()->load('creator');
    }

    public function update(int $id, CourseMutationData $data): Course
    {
        $course = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['code'])) {
            $this->ensureUniqueCode((string) $attributes['code'], $course->id);
        }

        /** @var Course $updated */
        $updated = $this->repository->update($course, $attributes);

        Event::dispatch(new CourseUpdated($updated->id, $updated->tenant_id));

        return $updated->refresh()->load('creator');
    }

    public function delete(int $id): void
    {
        $course = $this->find($id);

        $this->repository->delete($course);

        Event::dispatch(new CourseArchived($course->id, $course->tenant_id));
    }

    private function ensureUniqueCode(string $code, ?int $ignoreId = null): void
    {
        $query = Course::query()->where('tenant_id', $this->tenantId())->where('code', $code);

        if ($ignoreId !== null) {
            $query->whereKeyNot($ignoreId);
        }

        if ($query->exists()) {
            throw ValidationException::withMessages([
                'code' => ['Course code must be unique per tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
