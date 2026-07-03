<?php

namespace Modules\Institute\Application\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Modules\Institute\Domain\Enums\InstituteStatus;
use Modules\Institute\Domain\Events\InstituteRegistered;
use Modules\Institute\Domain\Models\Institute;
use Modules\Tenant\Application\Services\TenantContextService;

class InstituteRegistrationService
{
    public function __construct(
        private readonly TenantContextService $tenantContextService,
    ) {}

    public function register(array $attributes, User $actor): Institute
    {
        $tenantId = $this->tenantContextService->requiredTenantId();

        return DB::transaction(function () use ($attributes, $actor, $tenantId): Institute {
            $institute = Institute::query()->create([
                'tenant_id' => $tenantId,
                'name' => $attributes['name'],
                'slug' => $attributes['slug'] ?? Str::slug($attributes['name']),
                'code' => $attributes['code'],
                'status' => InstituteStatus::Provisioning,
                'email' => $attributes['email'] ?? null,
                'phone' => $attributes['phone'] ?? null,
                'website' => $attributes['website'] ?? null,
                'description' => $attributes['description'] ?? null,
                'address' => $attributes['address'] ?? null,
                'branding' => [
                    'logo_url' => null,
                    'primary_color' => $attributes['primary_color'] ?? '#0b6eff',
                    'secondary_color' => $attributes['secondary_color'] ?? '#00a889',
                ],
                'configuration' => [
                    'timezone' => $attributes['timezone'] ?? config('app.timezone'),
                    'locale' => $attributes['locale'] ?? config('app.locale'),
                ],
                'onboarding_step' => 'institute_registered',
                'created_by_user_id' => $actor->id,
            ]);

            Event::dispatch(new InstituteRegistered(
                instituteId: $institute->id,
                actorUserId: $actor->id,
            ));

            return $institute->refresh();
        });
    }
}
