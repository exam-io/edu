import { Navigate, Outlet } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';

interface PermissionRouteProps {
    requiredPermissions?: string[];
    requiredRoles?: string[];
    fallbackTo?: string;
}

export function PermissionRoute({
    requiredPermissions = [],
    requiredRoles = [],
    fallbackTo = '/',
}: PermissionRouteProps) {
    const roles = useAuthStore((state) => state.roles);
    const permissions = useAuthStore((state) => state.permissions);

    const rolesAllowed = requiredRoles.length === 0 || requiredRoles.some((role) => roles.includes(role));
    const permissionsAllowed =
        requiredPermissions.length === 0 || requiredPermissions.some((permission) => permissions.includes(permission));

    if (!rolesAllowed || !permissionsAllowed) {
        return <Navigate to={fallbackTo} replace />;
    }

    return <Outlet />;
}
