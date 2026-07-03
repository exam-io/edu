import { type PropsWithChildren } from 'react';
import { TenantBootstrapProvider } from '@modules/tenant/providers/TenantBootstrapProvider';

export function TenantProvider({ children }: PropsWithChildren) {
    return <TenantBootstrapProvider>{children}</TenantBootstrapProvider>;
}
