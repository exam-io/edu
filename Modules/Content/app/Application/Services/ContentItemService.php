<?php

namespace Modules\Content\Application\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Event;
use Illuminate\Validation\ValidationException;
use Modules\Content\Application\Contracts\ContentItemServiceInterface;
use Modules\Content\Application\Contracts\ContentRepositoryInterface;
use Modules\Content\Application\DTOs\ContentListQueryData;
use Modules\Content\Application\DTOs\ContentMutationData;
use Modules\Content\Domain\Events\ContentItemPublished;
use Modules\Content\Domain\Models\ContentItem;
use Modules\Content\Domain\Models\CourseSection;
use Modules\Course\Domain\Models\Course;
use Modules\Media\Domain\Models\MediaAsset;
use Modules\Tenant\Application\Services\TenantContextService;

class ContentItemService implements ContentItemServiceInterface
{
    public function __construct(
        private readonly ContentRepositoryInterface $repository,
        private readonly TenantContextService $tenantContext,
    ) {}

    public function list(ContentListQueryData $query): LengthAwarePaginator
    {
        return $this->repository->paginate(
            modelClass: ContentItem::class,
            tenantId: $this->tenantId(),
            query: $query,
            searchColumns: ['title', 'content_body'],
            filters: [
                'course_id' => $query->courseId,
                'course_section_id' => $query->courseSectionId,
                'content_type' => $query->contentType,
            ],
            with: ['course', 'section', 'mediaAsset'],
        );
    }

    public function find(int $id): ContentItem
    {
        $item = $this->repository->findForTenant(ContentItem::class, $this->tenantId(), $id, ['course', 'section', 'mediaAsset']);

        if (! $item instanceof ContentItem) {
            throw (new ModelNotFoundException())->setModel(ContentItem::class, [$id]);
        }

        return $item;
    }

    public function create(ContentMutationData $data): ContentItem
    {
        $attributes = $data->attributes;
        $this->assertCourse((int) $attributes['course_id']);
        $this->assertSection($attributes['course_section_id'] ?? null);
        $this->assertMedia($attributes['media_asset_id'] ?? null);

        $attributes['tenant_id'] = $this->tenantId();

        /** @var ContentItem $item */
        $item = $this->repository->create(ContentItem::class, $attributes);

        if (($item->status ?? null) === 'published') {
            Event::dispatch(new ContentItemPublished($item->id, $item->course_id, $item->tenant_id));
        }

        return $item->refresh()->load(['course', 'section', 'mediaAsset']);
    }

    public function update(int $id, ContentMutationData $data): ContentItem
    {
        $item = $this->find($id);
        $attributes = $data->attributes;

        if (isset($attributes['course_id'])) {
            $this->assertCourse((int) $attributes['course_id']);
        }
        if (array_key_exists('course_section_id', $attributes)) {
            $this->assertSection($attributes['course_section_id']);
        }
        if (array_key_exists('media_asset_id', $attributes)) {
            $this->assertMedia($attributes['media_asset_id']);
        }

        /** @var ContentItem $updated */
        $updated = $this->repository->update($item, $attributes);

        if (($updated->status ?? null) === 'published') {
            Event::dispatch(new ContentItemPublished($updated->id, $updated->course_id, $updated->tenant_id));
        }

        return $updated->refresh()->load(['course', 'section', 'mediaAsset']);
    }

    public function delete(int $id): void
    {
        $item = $this->find($id);
        $this->repository->delete($item);
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

    private function assertSection(mixed $sectionId): void
    {
        if ($sectionId === null) {
            return;
        }

        $exists = CourseSection::query()->where('tenant_id', $this->tenantId())->whereKey((int) $sectionId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'course_section_id' => ['Section must belong to tenant.'],
            ]);
        }
    }

    private function assertMedia(mixed $mediaAssetId): void
    {
        if ($mediaAssetId === null) {
            return;
        }

        $exists = MediaAsset::query()->where('tenant_id', $this->tenantId())->whereKey((int) $mediaAssetId)->exists();

        if (! $exists) {
            throw ValidationException::withMessages([
                'media_asset_id' => ['Media asset must belong to tenant.'],
            ]);
        }
    }

    private function tenantId(): int
    {
        return $this->tenantContext->requiredTenantId();
    }
}
