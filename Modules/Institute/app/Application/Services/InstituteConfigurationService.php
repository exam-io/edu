<?php

namespace Modules\Institute\Application\Services;

use Illuminate\Support\Facades\Cache;
use Modules\Institute\Domain\Models\Institute;

class InstituteConfigurationService
{
    private const CACHE_TTL = 1800;

    public function getConfiguration(Institute $institute): array
    {
        return Cache::remember($this->cacheKey($institute->id), self::CACHE_TTL, function () use ($institute): array {
            $configuration = $institute->configuration ?? [];

            return [
                'timezone' => $configuration['timezone'] ?? config('app.timezone'),
                'locale' => $configuration['locale'] ?? config('app.locale'),
                'features' => $configuration['features'] ?? ['onboarding', 'academic-session-management'],
                'onboarding_step' => $institute->onboarding_step,
                'onboarded' => $institute->onboarded_at !== null,
            ];
        });
    }

    public function updateConfiguration(Institute $institute, array $attributes): Institute
    {
        $configuration = array_merge($this->getConfiguration($institute), array_filter([
            'timezone' => $attributes['timezone'] ?? null,
            'locale' => $attributes['locale'] ?? null,
            'features' => $attributes['features'] ?? null,
        ], static fn ($value) => $value !== null));

        $institute->update([
            'configuration' => $configuration,
            'onboarding_step' => 'configuration_updated',
        ]);

        $this->invalidateCache($institute->id);

        return $institute->refresh();
    }

    public function onboardingWizard(Institute $institute): array
    {
        $session = $institute->currentAcademicSession;

        return [
            'current_step' => $institute->onboarding_step,
            'is_completed' => $institute->onboarded_at !== null,
            'steps' => [
                [
                    'key' => 'institute_registered',
                    'title' => 'Institute Profile',
                    'completed' => $institute->created_at !== null,
                ],
                [
                    'key' => 'branding_updated',
                    'title' => 'Branding',
                    'completed' => !empty($institute->branding),
                ],
                [
                    'key' => 'academic_session_created',
                    'title' => 'Academic Session',
                    'completed' => $session !== null,
                ],
                [
                    'key' => 'configuration_updated',
                    'title' => 'Configuration',
                    'completed' => !empty($institute->configuration),
                ],
            ],
        ];
    }

    public function markOnboarded(Institute $institute): Institute
    {
        $institute->update([
            'onboarding_step' => 'completed',
            'onboarded_at' => now(),
        ]);

        return $institute->refresh();
    }

    public function invalidateCache(int $instituteId): void
    {
        Cache::forget($this->cacheKey($instituteId));
    }

    private function cacheKey(int $instituteId): string
    {
        return "institute:{$instituteId}:configuration";
    }
}
