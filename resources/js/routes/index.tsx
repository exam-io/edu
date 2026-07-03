import { GuestRoute } from '@modules/auth/routes/GuestRoute';
import { PermissionRoute } from '@modules/auth/routes/PermissionRoute';
import { ProtectedRoute } from '@modules/auth/routes/ProtectedRoute';
import { ForgotPasswordPage } from '@modules/auth/pages/ForgotPasswordPage';
import { LoginPage } from '@modules/auth/pages/LoginPage';
import { ResetPasswordPage } from '@modules/auth/pages/ResetPasswordPage';
import { VerifyEmailPage } from '@modules/auth/pages/VerifyEmailPage';
import { Navigate, Route, Routes } from 'react-router-dom';

export function AppRoutes() {
    return (
        <Routes>
            <Route element={<GuestRoute />}>
                <Route path="/auth/login" element={<LoginPage />} />
                <Route path="/auth/forgot-password" element={<ForgotPasswordPage />} />
                <Route path="/auth/reset-password" element={<ResetPasswordPage />} />
            </Route>

            <Route element={<ProtectedRoute />}>
                <Route element={<PermissionRoute />}>
                    <Route path="/auth/verify-email" element={<VerifyEmailPage />} />
                    <Route
                        path="/"
                        element={<div className="p-6 text-sm text-[var(--color-muted)]">Authenticated session ready.</div>}
                    />
                </Route>
            </Route>

            <Route path="*" element={<Navigate to="/auth/login" replace />} />
        </Routes>
    );
}
