import { type PropsWithChildren, useMemo } from 'react';
import { PermissionContext } from '@modules/auth/components/PermissionContext';
import type { PermissionContextValue } from '@modules/auth/components/PermissionContext';
import { useAuthStore } from '@modules/auth/store/authStore';

export function PermissionProvider({ children }: PropsWithChildren) {
    const roles = useAuthStore((state) => state.roles);
    const permissions = useAuthStore((state) => state.permissions);

    const value = useMemo<PermissionContextValue>(() => ({
        roles,
        permissions,
        hasRole: (role: string) => roles.includes(role),
        hasPermission: (permission: string) => permissions.includes(permission),
        hasAnyPermission: (permissionList: string[]) =>
            permissionList.some((permission: string) => permissions.includes(permission)),
    }), [permissions, roles]);

    return <PermissionContext.Provider value={value}>{children}</PermissionContext.Provider>;
}
