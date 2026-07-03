import { createContext } from 'react';

export interface PermissionContextValue {
    roles: string[];
    permissions: string[];
    hasRole: (role: string) => boolean;
    hasPermission: (permission: string) => boolean;
    hasAnyPermission: (permissionList: string[]) => boolean;
}

export const PermissionContext = createContext<PermissionContextValue | undefined>(undefined);
