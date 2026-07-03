import { type PropsWithChildren } from 'react';
import { TenantContext } from '@modules/auth/components/TenantContext';
import { useAuthStore } from '@modules/auth/store/authStore';

export function TenantProvider({ children }: PropsWithChildren) {
    const tenant = useAuthStore((state) => state.tenant);

    return (
        <TenantContext.Provider value={{ tenant, hasTenant: tenant !== null }}>
            {children}
        </TenantContext.Provider>
    );
}
