import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';
import { getRoleDefaultPath } from '@modules/auth/utils/roleDashboard';

interface GuestRouteProps {
    redirectTo?: string;
}

export function GuestRoute({ redirectTo = '/' }: GuestRouteProps) {
    const isAuthenticated = useAuthStore((state) => state.isAuthenticated);
    const loading = useAuthStore((state) => state.loading);
    const roles = useAuthStore((state) => state.roles);
    const location = useLocation();

    if (loading) {
        return <div className="p-6 text-sm text-[var(--color-muted)]">Loading...</div>;
    }

    if (isAuthenticated) {
        const landingPath = redirectTo === '/' ? getRoleDefaultPath(roles) : redirectTo;
        return <Navigate to={landingPath} state={{ from: location }} replace />;
    }

    return <Outlet />;
}
