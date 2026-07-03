<?php

namespace Modules\Institute\Application\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Modules\Institute\Domain\Events\InstituteBrandingUpdated;
use Modules\Institute\Domain\Models\Institute;

class InstituteBrandingService
{
    private const CACHE_TTL = 3600;

    public function getBranding(Institute $institute): array
    {
        return Cache::remember($this->cacheKey($institute->id), self::CACHE_TTL, function () use ($institute): array {
            $branding = $institute->branding ?? [];

            return [
                'logo_url' => $branding['logo_url'] ?? null,
                'primary_color' => $branding['primary_color'] ?? '#0b6eff',
                'secondary_color' => $branding['secondary_color'] ?? '#00a889',
                'tagline' => $branding['tagline'] ?? null,
            ];
        });
    }

    public function updateBranding(Institute $institute, array $attributes): Institute
    {
        $branding = array_merge($this->getBranding($institute), array_filter([
            'logo_url' => $attributes['logo_url'] ?? null,
            'primary_color' => $attributes['primary_color'] ?? null,
            'secondary_color' => $attributes['secondary_color'] ?? null,
            'tagline' => $attributes['tagline'] ?? null,
        ], static fn ($value) => $value !== null));

        $institute->update([
            'branding' => $branding,
            'onboarding_step' => 'branding_updated',
        ]);

        $this->invalidateCache($institute->id);

        Event::dispatch(new InstituteBrandingUpdated($institute->id));

        return $institute->refresh();
    }

    public function invalidateCache(int $instituteId): void
    {
        Cache::forget($this->cacheKey($instituteId));
    }

    private function cacheKey(int $instituteId): string
    {
        return "institute:{$instituteId}:branding";
    }
}
