<?php

namespace Modules\Institute\Application\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Institute\Domain\Enums\InstituteStatus;
use Modules\Institute\Domain\Events\InstituteProvisioned;
use Modules\Institute\Domain\Events\InstituteProvisioningStarted;
use Modules\Institute\Domain\Models\Institute;

class InstituteProvisioningService
{
    public function __construct(
        private readonly AcademicSessionService $academicSessionService,
    ) {}

    public function provision(int $instituteId, ?int $actorUserId = null): Institute
    {
        return DB::transaction(function () use ($instituteId, $actorUserId): Institute {
            /** @var Institute $institute */
            $institute = Institute::query()->findOrFail($instituteId);

            Event::dispatch(new InstituteProvisioningStarted($institute->id));

            $defaultSession = $this->academicSessionService->createDefaultSession($institute, $actorUserId);

            $institute->update([
                'status' => InstituteStatus::Active,
                'onboarding_step' => 'academic_session_created',
            ]);

            Event::dispatch(new InstituteProvisioned(
                instituteId: $institute->id,
                defaultAcademicSessionId: $defaultSession->id,
            ));

            return $institute->refresh();
        });
    }
}
