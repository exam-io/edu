import { GuestRoute } from '@modules/auth/routes/GuestRoute';
import { PermissionRoute } from '@modules/auth/routes/PermissionRoute';
import { ProtectedRoute } from '@modules/auth/routes/ProtectedRoute';
import { ForgotPasswordPage } from '@modules/auth/pages/ForgotPasswordPage';
import { LoginPage } from '@modules/auth/pages/LoginPage';
import { ResetPasswordPage } from '@modules/auth/pages/ResetPasswordPage';
import { VerifyEmailPage } from '@modules/auth/pages/VerifyEmailPage';
import { InstituteOnboardingPage } from '@modules/institutes/pages/InstituteOnboardingPage';
import {
    AcademicHomePage,
    BatchesPage,
    ClassesPage,
    DepartmentsPage,
    ProgramsPage,
    SectionsPage,
    SubjectsPage,
} from '@modules/academic';
import { Navigate, Route, Routes } from 'react-router-dom';
import { TenantBootstrapProvider } from '@modules/tenant';

export function AppRoutes() {
    return (
        <TenantBootstrapProvider>
            <Routes>
                <Route element={<GuestRoute />}>
                    <Route path="/auth/login" element={<LoginPage />} />
                    <Route path="/auth/forgot-password" element={<ForgotPasswordPage />} />
                    <Route path="/auth/reset-password" element={<ResetPasswordPage />} />
                </Route>

                <Route element={<ProtectedRoute />}>
                    <Route element={<PermissionRoute />}>
                        <Route path="/auth/verify-email" element={<VerifyEmailPage />} />
                        <Route path="/institutes/onboarding" element={<InstituteOnboardingPage />} />
                        <Route path="/academic" element={<AcademicHomePage />} />
                        <Route path="/academic/departments" element={<DepartmentsPage />} />
                        <Route path="/academic/programs" element={<ProgramsPage />} />
                        <Route path="/academic/classes" element={<ClassesPage />} />
                        <Route path="/academic/sections" element={<SectionsPage />} />
                        <Route path="/academic/batches" element={<BatchesPage />} />
                        <Route path="/academic/subjects" element={<SubjectsPage />} />
                        <Route
                            path="/"
                            element={<Navigate to="/institutes/onboarding" replace />}
                        />
                    </Route>
                </Route>

                <Route path="*" element={<Navigate to="/auth/login" replace />} />
            </Routes>
        </TenantBootstrapProvider>
    );
}
