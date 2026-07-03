<?php

namespace Modules\Tenant\Application\Services;

use Illuminate\Http\Request;
use Modules\Tenant\Application\Contracts\TenantRepositoryInterface;
use Modules\Tenant\Domain\Models\Tenant;

/**
 * Multi-strategy tenant resolution service.
 * 
 * Resolves tenant in priority order:
 * 1. X-Tenant-ID header (direct ID lookup)
 * 2. ?tenant query parameter (development only)
 * 3. Custom domain mapping
 * 4. Subdomain extraction
 * 5. Primary domain lookup
 */
class TenantResolverService
{
    public function __construct(
        private readonly TenantRepositoryInterface $tenantRepository,
    ) {}

    public function resolve(Request $request): ?Tenant
    {
        // Strategy 1: X-Tenant-ID header (API direct resolution)
        if ($request->hasHeader('X-Tenant-ID')) {
            $tenantId = (int) $request->header('X-Tenant-ID');
            return $this->tenantRepository->findById($tenantId);
        }

        // Strategy 2: ?tenant query parameter (development only)
        if (app()->environment('local') && $request->has('tenant')) {
            $slug = $request->query('tenant');
            return $this->tenantRepository->findBySlug($slug);
        }

        $host = $request->getHost();

        // Strategy 3: Custom domain mapping
        $tenant = $this->tenantRepository->findByCustomDomain($host);
        if ($tenant !== null) {
            return $tenant;
        }

        // Strategy 4: Subdomain extraction
        $tenant = $this->resolveFromSubdomain($host);
        if ($tenant !== null) {
            return $tenant;
        }

        // Strategy 5: Primary domain lookup
        $tenant = $this->tenantRepository->findByDomain($host);

        if ($tenant !== null) {
            return $tenant;
        }

        // Strategy 6: Local development fallback
        if (app()->environment('local') && in_array($host, ['127.0.0.1', 'localhost'], true)) {
            return $this->tenantRepository->allActive()->first();
        }

        return null;
    }

    private function resolveFromSubdomain(string $host): ?Tenant
    {
        $baseDomain = config('tenancy.base_domain', $this->deriveBaseDomain());

        if (!str_ends_with($host, $baseDomain)) {
            return null;
        }

        $subdomain = substr($host, 0, -strlen($baseDomain) - 1);

        if (empty($subdomain) || str_contains($subdomain, '.')) {
            return null;
        }

        return $this->tenantRepository->findBySlug($subdomain);
    }

    private function deriveBaseDomain(): string
    {
        $appUrl = config('app.url', 'http://localhost');
        $host = parse_url($appUrl, PHP_URL_HOST) ?: 'localhost';

        if ($host === 'localhost') {
            return 'localhost';
        }

        // Extract base domain (e.g., example.com from app.example.com)
        $parts = explode('.', $host);
        if (count($parts) >= 2) {
            return implode('.', array_slice($parts, -2));
        }

        return $host;
    }
}
