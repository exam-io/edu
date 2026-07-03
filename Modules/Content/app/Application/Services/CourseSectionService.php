<?php

namespace Modules\Content\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Modules\Content\Application\Contracts\ContentRepositoryInterface;
use Modules\Content\Application\Contracts\CourseSectionServiceInterface;
use Modules\Content\Application\DTOs\ContentListQueryData;
use Modules\Content\Application\DTOs\ContentMutationData;
use Modules\Content\Domain\Models\CourseSection;
use Modules\Course\Domain\Models\Course;
use Modules\Tenant\Application\Services\TenantContextService;

class CourseSectionService implements CourseSectionServiceInterface
{
    public function __construct(
        private readonly ContentRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(ContentListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: CourseSection::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['title', 'description'],
            filters: [
                'course_id' => $query->courseId,
            ],
            with: ['course'],
        );
    }

    public function find(int $id): CourseSection
    {
        $section = $this->repository->findForTenant(CourseSection::class, $this->tenantId(), $id, ['course']);

        if (! $section instanceof CourseSection) {
            throw (new ModelNotFoundException())->setModel(CourseSection::class, [$id]);
        }

        return $section;
    }

    public function create(ContentMutationData $data): CourseSection
    {
        $attributes = $data->attributes;
        $this->assertCourse((int) $attributes['course_id']);
        $attributes['tenant_id'] = $this->tenantId();

        /** @var CourseSection $section */
        $section = $this->repository->create(CourseSection::class, $attributes);

        return $section->refresh()->load('course');
    }

    public function update(int $id, ContentMutationData $data): CourseSection
    {
        $section = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['course_id'])) {
            $this->assertCourse((int) $attributes['course_id']);
        }

        /** @var CourseSection $updated */
        $updated = $this->repository->update($section, $attributes);

        return $updated->refresh()->load('course');
    }

    public function delete(int $id): void
    {
        $section = $this->find($id);
        $this->repository->delete($section);
    }

    private function assertCourse(int $courseId): void
    {
        $exists = Course::query()->where('tenant_id', $this->tenantId())->whereKey($courseId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'course_id' => ['Course must belong to tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
