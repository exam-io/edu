import { useContext } from 'react';
import { PermissionContext } from '@modules/auth/components/PermissionContext';

export function usePermission() {
    const context = useContext(PermissionContext);

    if (context === undefined) {
        throw new Error('usePermission must be used within PermissionProvider.');
    }

    return context;
}
