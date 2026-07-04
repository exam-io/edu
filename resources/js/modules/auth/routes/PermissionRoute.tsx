import { Navigate, Outlet } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';
import { getRoleDefaultPath } from '@modules/auth/utils/roleDashboard';

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
    const roleLandingPath = getRoleDefaultPath(roles);

    const rolesAllowed = requiredRoles.length === 0 || requiredRoles.some((role) => roles.includes(role));
    const permissionsAllowed =
        requiredPermissions.length === 0 || requiredPermissions.some((permission) => permissions.includes(permission));

    if (!rolesAllowed || !permissionsAllowed) {
        return <Navigate to={fallbackTo === '/' ? roleLandingPath : fallbackTo} replace />;
    }

    return <Outlet />;
}
