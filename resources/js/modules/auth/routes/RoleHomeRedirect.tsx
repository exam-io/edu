import { Navigate } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';
import { getRoleDefaultPath } from '@modules/auth/utils/roleDashboard';

export function RoleHomeRedirect() {
    const roles = useAuthStore((state) => state.roles);
    const path = getRoleDefaultPath(roles);

    return <Navigate to={path} replace />;
}
