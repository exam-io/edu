import { Navigate, Outlet, useLocation } from 'react-router-dom';
import { useAuthStore } from '@modules/auth/store/authStore';

interface ProtectedRouteProps {
    redirectTo?: string;
}

export function ProtectedRoute({ redirectTo = '/auth/login' }: ProtectedRouteProps) {
    const isAuthenticated = useAuthStore((state) => state.isAuthenticated);
    const loading = useAuthStore((state) => state.loading);
    const location = useLocation();

    if (loading) {
        return <div className="p-6 text-sm text-[var(--color-muted)]">Loading...</div>;
    }

    if (!isAuthenticated) {
        return <Navigate to={redirectTo} state={{ from: location }} replace />;
    }

    return <Outlet />;
}
