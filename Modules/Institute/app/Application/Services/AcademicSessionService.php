<?php

namespace Modules\Institute\Application\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Institute\Domain\Enums\AcademicSessionStatus;
use Modules\Institute\Domain\Events\AcademicSessionCreated;
use Modules\Institute\Domain\Events\AcademicSessionDeleted;
use Modules\Institute\Domain\Events\AcademicSessionUpdated;
use Modules\Institute\Domain\Models\AcademicSession;
use Modules\Institute\Domain\Models\Institute;

class AcademicSessionService
{
    public function listForInstitute(Institute $institute)
    {
        return AcademicSession::query()
            ->where('institute_id', $institute->id)
            ->orderByDesc('is_current')
            ->orderByDesc('starts_on')
            ->get();
    }

    public function create(Institute $institute, array $attributes, ?int $actorUserId = null): AcademicSession
    {
        return DB::transaction(function () use ($institute, $attributes, $actorUserId): AcademicSession {
            $isCurrent = (bool) ($attributes['is_current'] ?? false);

            if ($isCurrent) {
                $this->clearCurrentFlag($institute->id);
            }

            $session = AcademicSession::query()->create([
                'institute_id' => $institute->id,
                'name' => $attributes['name'],
                'code' => $attributes['code'],
                'starts_on' => $attributes['starts_on'],
                'ends_on' => $attributes['ends_on'],
                'is_current' => $isCurrent,
                'status' => $attributes['status'] ?? AcademicSessionStatus::Planned,
                'metadata' => $attributes['metadata'] ?? null,
                'created_by_user_id' => $actorUserId,
            ]);

            Event::dispatch(new AcademicSessionCreated($session->id, $institute->id));

            return $session->refresh();
        });
    }

    public function update(Institute $institute, AcademicSession $session, array $attributes): AcademicSession
    {
        return DB::transaction(function () use ($institute, $session, $attributes): AcademicSession {
            if (array_key_exists('is_current', $attributes) && $attributes['is_current']) {
                $this->clearCurrentFlag($institute->id);
            }

            $session->update(array_filter([
                'name' => $attributes['name'] ?? null,
                'code' => $attributes['code'] ?? null,
                'starts_on' => $attributes['starts_on'] ?? null,
                'ends_on' => $attributes['ends_on'] ?? null,
                'is_current' => $attributes['is_current'] ?? null,
                'status' => $attributes['status'] ?? null,
                'metadata' => $attributes['metadata'] ?? null,
            ], static fn ($value) => $value !== null));

            Event::dispatch(new AcademicSessionUpdated($session->id, $institute->id));

            return $session->refresh();
        });
    }

    public function delete(Institute $institute, AcademicSession $session): void
    {
        $sessionId = $session->id;
        $session->delete();

        Event::dispatch(new AcademicSessionDeleted($sessionId, $institute->id));
    }

    public function createDefaultSession(Institute $institute, ?int $actorUserId = null): AcademicSession
    {
        $existingCurrent = AcademicSession::query()
            ->where('institute_id', $institute->id)
            ->where('is_current', true)
            ->first();

        if ($existingCurrent !== null) {
            return $existingCurrent;
        }

        $year = (int) now()->format('Y');

        return $this->create($institute, [
            'name' => sprintf('%d-%d', $year, $year + 1),
            'code' => sprintf('AY-%d', $year),
            'starts_on' => now()->startOfYear()->toDateString(),
            'ends_on' => now()->endOfYear()->toDateString(),
            'is_current' => true,
            'status' => AcademicSessionStatus::Active,
            'metadata' => [
                'source' => 'provisioning-default',
            ],
        ], $actorUserId);
    }

    private function clearCurrentFlag(int $instituteId): void
    {
        AcademicSession::query()
            ->where('institute_id', $instituteId)
            ->where('is_current', true)
            ->update(['is_current' => false]);
    }
}
