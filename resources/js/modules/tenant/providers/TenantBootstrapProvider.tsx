import { useEffect, type PropsWithChildren } from 'react';
import { useTenantStore } from '@modules/tenant/store/tenantStore';
import { tenantService } from '@modules/tenant/services/tenantService';

/**
 * Tenant bootstrap provider - loads tenant context on app startup.
 * Runs optimistically: hydrates in background using localStorage cache/defaults.
 * Does not block rendering.
 */
export function TenantBootstrapProvider({ children }: PropsWithChildren) {
    const setLoading = useTenantStore((state) => state.setLoading);
    const setError = useTenantStore((state) => state.setError);
    const bootstrap = useTenantStore((state) => state.bootstrap);

    useEffect(() => {
        setLoading(true);

        tenantService
            .getCurrentTenant()
            .then((data) => {
                bootstrap(data.tenant, data.branding, data.configuration);
                setError(null);
            })
            .catch((error) => {
                setError(error instanceof Error ? error.message : 'Failed to load tenant');
            })
            .finally(() => {
                setLoading(false);
            });
    }, [bootstrap, setLoading, setError]);

    return <>{children}</>;
}
