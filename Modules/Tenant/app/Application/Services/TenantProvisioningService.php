<?php

namespace Modules\Tenant\Application\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Domain\Enums\TenantStatus;
use Modules\Tenant\Domain\Events\TenantCreated;
use Modules\Tenant\Domain\Models\Tenant;
use Modules\Tenant\Domain\Models\TenantSetting;

/**
 * Tenant provisioning service - atomic creation with side effects.
 */
class TenantProvisioningService
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository,
    ) {}

    /**
     * Provision a new tenant atomically.
     * 
     * @throws \Exception
     */
    public function provision(ProvisionTenantDto $input): Tenant
    {
        return DB::transaction(function () use ($input) {
            // Create tenant
            $tenant = Tenant::create([
                'name' => $input->name,
                'slug' => $input->slug,
                'domain' => $input->domain,
                'custom_domain' => $input->customDomain,
                'status' => TenantStatus::PROVISIONING->value,
                'plan' => $input->plan ?? 'free',
            ]);

            // Create default settings
            TenantSetting::create([
                'tenant_id' => $tenant->id,
                'theme' => $input->theme ?? 'light',
                'language' => $input->language ?? config('app.locale'),
                'timezone' => $input->timezone ?? config('app.timezone'),
                'primary_color' => $input->primaryColor ?? '#0b6eff',
                'secondary_color' => $input->secondaryColor ?? '#00a889',
            ]);

            // Dispatch event - listeners handle storage init, notifications, etc.
            Event::dispatch(new TenantCreated($tenant->id));

            return $tenant;
        });
    }

    /**
     * Activate a provisioning tenant.
     */
    public function activate(Tenant $tenant): void
    {
        DB::transaction(function () use ($tenant) {
            $tenant->update([
                'status' => TenantStatus::ACTIVE->value,
                'provisioned_at' => now(),
            ]);

            Event::dispatch(new \Modules\Tenant\Domain\Events\TenantActivated($tenant->id));
        });
    }

    /**
     * Initialize tenant storage directory.
     */
    public function initializeStorage(Tenant $tenant): void
    {
        $basePath = "tenants/{$tenant->id}";

        // Create base directories
        foreach (['documents', 'uploads', 'exports', 'cache'] as $dir) {
            Storage::disk('local')->makeDirectory("{$basePath}/{$dir}");
        }
    }
}

/**
 * Data transfer object for provisioning input.
 */
readonly class ProvisionTenantDto
{
    public function __construct(
        public string $name,
        public string $slug,
        public string $domain,
        public ?string $customDomain = null,
        public ?string $plan = null,
        public ?string $theme = null,
        public ?string $language = null,
        public ?string $timezone = null,
        public ?string $primaryColor = null,
        public ?string $secondaryColor = null,
    ) {}
}
