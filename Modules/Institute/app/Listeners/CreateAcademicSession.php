<?php

namespace Modules\Institute\Listeners;

use Modules\Institute\Application\Services\AcademicSessionService;
use Modules\Institute\Domain\Enums\InstituteStatus;
use Modules\Institute\Domain\Events\InstituteActivated;
use Modules\Institute\Domain\Events\InstituteRegistered;
use Modules\Institute\Domain\Models\Institute;

class CreateAcademicSession
{
    public function __construct(
        private readonly AcademicSessionService $academicSessionService,
    ) {}

    public function handle(InstituteRegistered $event): void
    {
        $institute = Institute::query()->find($event->instituteId);

        if ($institute === null) {
            return;
        }

        $this->academicSessionService->createDefaultSession($institute, $event->actorUserId);

        $institute->update([
            'status' => InstituteStatus::Active,
            'onboarding_step' => 'academic_session_created',
        ]);

        event(new InstituteActivated($institute->id, $event->actorUserId));
    }
}
