import { useContext } from 'react';
import { TenantContext } from '@modules/auth/components/TenantContext';

export function useTenant() {
    const context = useContext(TenantContext);

    if (context === undefined) {
        throw new Error('useTenant must be used within TenantProvider.');
    }

    return context;
}
