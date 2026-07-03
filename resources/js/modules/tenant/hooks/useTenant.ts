import { useCallback } from 'react';
import { useTenantStore } from '@modules/tenant/store/tenantStore';

/**
 * Hook to access tenant context data.
 */
export function useTenant() {
    const tenant = useTenantStore((state) => state.tenant);
    const branding = useTenantStore((state) => state.branding);
    const configuration = useTenantStore((state) => state.configuration);
    const isLoading = useTenantStore((state) => state.isLoading);
    const error = useTenantStore((state) => state.error);

    const hasTenant = tenant !== null;

    return {
        tenant,
        branding,
        configuration,
        isLoading,
        error,
        hasTenant,
    };
}

/**
 * Hook to check if a feature is available for the current tenant.
 */
export function useTenantFeature(featureName: string): boolean {
    const configuration = useTenantStore((state) => state.configuration);
    return configuration?.features.includes(featureName) ?? false;
}

/**
 * Hook to get tenant plan limits.
 */
export function useTenantLimits() {
    const configuration = useTenantStore((state) => state.configuration);

    return useCallback(
        () => ({
            maxUsers: configuration?.max_users ?? 5,
            maxStorage: configuration?.max_storage ?? 1,
        }),
        [configuration]
    );
}
