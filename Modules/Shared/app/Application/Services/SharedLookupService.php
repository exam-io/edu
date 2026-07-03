<?php

namespace Modules\Shared\Application\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Modules\Academic\Domain\Models\AcademicClass;
use Modules\Academic\Domain\Models\Batch;
use Modules\Academic\Domain\Models\Section;
use Modules\Academic\Domain\Models\Subject;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Parent\Domain\Models\ParentProfile;
use Modules\Shared\Application\Contracts\SharedLookupServiceInterface;
use Modules\Student\Domain\Models\Student;
use Modules\Teacher\Domain\Models\Teacher;
use Modules\Tenant\Application\Services\TenantContextService;

class SharedLookupService implements SharedLookupServiceInterface
{
    /** @var list<string> */
    private const SUPPORTED_TYPES = [
        'students',
        'teachers',
        'parents',
        'classes',
        'sections',
        'batches',
        'subjects',
        'sessions',
    ];

    public function __construct(
        private readonly TenantContextService $tenantContext,
    ) {
    }

    public function supportedTypes(): array
    {
        return self::SUPPORTED_TYPES;
    }

    public function list(string $type, ?string $search = null, int $limit = 25): Collection
    {
        $query = $this->queryForType($type);

        if ($search !== null && $search !== '') {
            $this->applySearch($query, $type, $search);
        }

        $records = $query->limit(max(1, min($limit, 100)))->get();

        return $records->map(fn ($record) => $this->mapRecord($type, $record));
    }

    public function find(string $type, string $id): array
    {
        $query = $this->queryForType($type);
        $record = $query->whereKey((int) $id)->first();

        if ($record === null) {
            throw (new ModelNotFoundException())->setModel($type, [$id]);
        }

        return $this->mapRecord($type, $record);
    }

    private function queryForType(string $type): Builder
    {
        $tenantId = $this->tenantContext->requiredTenantId();

        return match ($type) {
            'students' => Student::query()->where('tenant_id', $tenantId)->orderBy('first_name'),
            'teachers' => Teacher::query()->where('tenant_id', $tenantId)->orderBy('first_name'),
            'parents' => ParentProfile::query()->where('tenant_id', $tenantId)->orderBy('first_name'),
            'classes' => AcademicClass::query()->where('tenant_id', $tenantId)->orderBy('name'),
            'sections' => Section::query()->where('tenant_id', $tenantId)->orderBy('name'),
            'batches' => Batch::query()->where('tenant_id', $tenantId)->orderBy('name'),
            'subjects' => Subject::query()->where('tenant_id', $tenantId)->orderBy('name'),
            'sessions' => AcademicSession::query()
                ->whereHas('institute', fn (Builder $query) => $query->where('tenant_id', $tenantId))
                ->orderByDesc('is_current')
                ->orderBy('starts_on'),
            default => throw new \InvalidArgumentException('Unsupported shared lookup type.'),
        };
    }

    private function applySearch(Builder $query, string $type, string $search): void
    {
        $like = '%'.trim($search).'%';

        match ($type) {
            'students', 'teachers', 'parents' => $query->where(function (Builder $nested) use ($like): void {
                $nested->where('first_name', 'like', $like)
                    ->orWhere('last_name', 'like', $like)
                    ->orWhere('email', 'like', $like);
            }),
            'classes', 'sections', 'batches', 'subjects', 'sessions' => $query->where('name', 'like', $like),
            default => null,
        };
    }

    /** @return array<string, mixed> */
    private function mapRecord(string $type, object $record): array
    {
        return match ($type) {
            'students' => [
                'id' => $record->id,
                'label' => trim($record->first_name.' '.$record->last_name),
                'code' => $record->admission_no,
                'status' => $record->status,
            ],
            'teachers' => [
                'id' => $record->id,
                'label' => trim($record->first_name.' '.$record->last_name),
                'code' => $record->employee_no,
                'status' => $record->status,
            ],
            'parents' => [
                'id' => $record->id,
                'label' => trim($record->first_name.' '.$record->last_name),
                'relationship' => $record->relationship,
                'status' => $record->status,
            ],
            'classes', 'sections', 'batches', 'subjects' => [
                'id' => $record->id,
                'label' => $record->name,
                'code' => $record->code,
                'status' => $record->status,
            ],
            'sessions' => [
                'id' => $record->id,
                'label' => $record->name,
                'code' => $record->code,
                'is_current' => (bool) $record->is_current,
                'status' => $record->status,
            ],
            default => throw new \InvalidArgumentException('Unsupported shared lookup type.'),
        };
    }
}
