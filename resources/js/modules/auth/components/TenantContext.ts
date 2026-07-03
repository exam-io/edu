import { createContext } from 'react';
import type { AuthTenant } from '@modules/auth/types/auth';

export interface TenantContextValue {
    tenant: AuthTenant | null;
    hasTenant: boolean;
}

export const TenantContext = createContext<TenantContextValue | undefined>(undefined);
